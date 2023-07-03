<?php

namespace App\UseCases;


use Carbon\Carbon;
use DomainException;
use App\Models\Application;
use Illuminate\Http\Request;
use App\UseCases\CommentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\application\ApplicationEditRequest;
use App\Http\Requests\application\ApplicationCreateRequest;


class ApplicationService
{
    
    private $commentService;

    public function __construct( CommentService $commentService)
    {
    $this->commentService = $commentService;
    }
    public function dash(Request $request)
    {
        $all = $this->commonAll($request)->count();
        // ----------------------------//
        $all_by_mont=$this->commonAll($request)
        ->selectRaw('year(updated_at) year, monthname(updated_at) month, count(*) total')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->get();
        //-------------------------------//
        $by_status = $this->commonAll($request)
        ->groupBy('status')
        ->selectRaw('count(*) as total, status')
        ->get();
        // ------------------------------
        $by_cert = $this->commonAll($request)
        ->whereNotNull('certificates')
        ->selectRaw('year(updated_at) year, monthname(updated_at) month, count(*) total')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->get();
        // --------------------------------//
        $by_lic = $this->commonAll($request)
        ->whereNotNull('licenses')
        ->selectRaw('year(updated_at) year, monthname(updated_at) month, count(*) total')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->get();
        // --------------------------------//

        $responce = [
            "applications" => $all,
            "allInMont"=>$all_by_mont,
            "certificates" => $by_cert,
            "licenses" => $by_lic,
            "status"=>$by_status,


        ];

        return response($responce);


    }

    public function list(Request $request){
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query = QueryBuilder::for(Application::class);
        if (!empty($request->get('search'))) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }
        if ($request->filled('from','to')) {
            $from = Carbon::createFromFormat('Y-m-d',$request->from)->startOfDay();
            $to = Carbon::createFromFormat('Y-m-d',$request->to)->endOfDay();
             $query->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);
            }
        $query->allowedAppends(!empty($request->append) ? explode(',', $request->get('append')) : []);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);

    }

    public function create(ApplicationCreateRequest $request)
    {
        $app = Application::make($request->only([ 
            'name',
            'staffs',
            'scope_and_purpose',
            'importance_id',
            'document_id',
            'techniques',
            'devices',
            'licenses',
            'certificates',
            'telecommunications',
            'error_or_broken',
            'provide_cyber_security',
            'threats_to_information_security',
            'consequences_of_an_incident',
            'organizational_and_technical_measures_to_ensure_security',
            'subject_id',
            
        ]));
        $app->user_id = Auth::user()->id;
        $app->save();
        return $app;
    }

    public function edit(ApplicationEditRequest $request, Application $application)
    {
        
        $application->update($request->only([
            'name',
            'staffs',
            'scope_and_purpose',
            'importance_id',
            'document_id',
            'techniques',
            'devices',
            'licenses',
            'certificates',
            'telecommunications',
            'error_or_broken',
            'provide_cyber_security',
            'threats_to_information_security',
            'consequences_of_an_incident',
            'organizational_and_technical_measures_to_ensure_security',
            'subject_id',
            
        ])+['status'=>0]);
            //  Application::findOrFail($application->id)->update(['status'=>0]);
            return $application;
    }

    public function remove(Application $application)
    {  
       
            $application->delete();
            return 'deleted';
        
        
    }

    private function commonAll(Request $request)
    {
        $query = QueryBuilder::for(Application::class);
        if(Gate::denies('user')){
            $query->withoutGlobalScope('permission');
        }
        if ($request->filled('from','to')) {
            $from = Carbon::createFromFormat('Y-m-d',$request->from)->startOfDay();
            $to = Carbon::createFromFormat('Y-m-d',$request->to)->endOfDay();
            return $query->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);
            }

        return $query;
    }

    public function writeComment(Request $request, Application $application){

        
        DB::beginTransaction();
        try {


            for ($n = 1; $n < 15; $n++) {
                if ($request->filled('column_' . $n)) {

                    $this->commentService->create([
                        'application_id' => $application->id,
                        'description' => $request->get('column_' . $n),
                        'column_id' => $n,
                        'author' => Auth::user()->id
                    ]);
                }
            }
            if (Gate::allows('manager') && $request->has('status')) {
                $application->update(['status' => Application::STATUS_MANAGER_TO_USER]);
            } elseif (Gate::allows('admin') && $request->has('status')) {
                $application->update(['status' => Application::STATUS_ADMIN_TO_MANAGER]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }

        return $application;
    }
   
  


}
