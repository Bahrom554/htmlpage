<?php

namespace App\UseCases;


use Carbon\Carbon;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Requests\application\ApplicationEditRequest;
use App\Http\Requests\application\ApplicationCreateRequest;

class ApplicationService
{
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
        ->whereNotNull('certificate_id')
        ->selectRaw('year(updated_at) year, monthname(updated_at) month, count(*) total')
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->get();
        // --------------------------------//
        $by_lic = $this->commonAll($request)
        ->whereNotNull('license_id')
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
        return $query->paginate($request->per_page);

    }

    public function create(ApplicationCreateRequest $request)
    {
        $app = Application::make($request->only(
        'name',
        'staffs',
        'scope_and_purpose',
        'error_or_broken',
        'devices',
        'license_id',
        'certificate_id',
        'telecommunications',
        'provide_cyber_security',
        'threats_to_information_security',
        'consequences_of_an_incident',
        'organizational_and_technical_measures_to_ensure_security',
        'subject',
        'subject_type',
        'subject_definition',
        'subject_document'
        ));
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
            'error_or_broken',
            'devices',
            'license_id',
            'certificate_id',
            'telecommunications',
            'provide_cyber_security',
            'threats_to_information_security',
            'consequences_of_an_incident',
            'organizational_and_technical_measures_to_ensure_security',
            'subject',
            'subject_type',
            'subject_definition',
            'subject_document'
            ]));
            return $application;
    }

    public function remove(Application $application)
    {
        $application->delete();
        return 'deleted';
    }

    public function reject(Request $request, Application $application){

        $request->validate([
            'reason' => 'nullable|string'
        ]);
        $application->status=Application::STATUS_REJECT;
        $application->rejected_at=Carbon::now();
        if($request->filled('reason')){
            $application->reason=$request->reason;
            $application->importance_id=null;
        }
        $application->save();
        return $application;
    }
    public function register(Request $request, Application $application){
        $request->validate([
            'reason' => 'nullable|string'
        ]);
        $application->status=Application::STATUS_PROCESS;
        if($request->filled('reason')){
            $application->reason=$request->reason;
        }
        $application->save();
        return $application;
    }
    public function success(Request $request, Application $application){
        $request->validate([
            'reason' => 'nullable|string'
        ]);
        $application->status=Application::STATUS_SUCCESS;
        if($request->filled('reason')){
            $application->reason=$request->reason;
        }
        $application->save();
        return $application;
    }
    public function importance(Request $request, Application $application){
        $request->validate([
            'importance_id' => 'nullable|integer|exists:importances,id'
        ]);
        if($request->filled('importance_id')){
            $application->importance_id=$request->importance_id;
        }
        $application->save();
        return $application;
    }

    public function rester(Request $request, Application $application){
        $application->status=Application::STATUS_WAITING;
        $application->save();
        return $application;
    }

    private function commonAll(Request $request)
    {
        $query = QueryBuilder::for(Application::class);
        $query->withoutGlobalScope('permission');
        if ($request->filled('from','to')) {
            $from = Carbon::createFromFormat('Y-m-d',$request->from)->startOfDay();
            $to = Carbon::createFromFormat('Y-m-d',$request->to)->endOfDay();
            return $query->whereBetween(DB::raw('DATE(created_at)'), [$from, $to]);
            }

        return $query;
    }


}
