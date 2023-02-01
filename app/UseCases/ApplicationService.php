<?php

namespace App\UseCases;


use App\Http\Requests\application\ApplicationCreateRequest;
use App\Http\Requests\application\ApplicationEditRequest;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

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
        $by_sub = DB::table('applications')
        ->leftJoin('users', 'applications.user_id', 'users.id')
        ->when($request->filled('between'),function($query) use($request){
         return $query->whereBetween('applications.updated_at', explode(',', $request->between));
        })
         ->groupBy('subject')
        ->selectRaw('count(*) as total, subject')
       ->get()
       ->sortByDesc('total');
        // ---------------------------------//
        $by_dev = DB::table('applications')
        ->leftJoin('devices', 'applications.device_id', 'devices.id')
        ->when($request->filled('between'),function($query) use($request){
            return $query->whereBetween('applications.updated_at', explode(',', $request->between));
           })
        ->groupBy('devices.id')
        ->selectRaw('count(*) as total, devices.*')
        ->get()
        ->sortByDesc('total');
        $responce = [
            "applications" => $all,
            "allInMont"=>$all_by_mont,
            "certificates" => $by_cert,
            "licenses" => $by_lic,
            "status"=>$by_status,
            "subject"=>$by_sub,
            "device"=>$by_dev
        ];

        return response($responce);


    }

    public function create(ApplicationCreateRequest $request)
    {
        $app = Application::make($request->only(
        'name',
        'staffs',
        'scope_and_purpose',
        'error_or_broken',
        'devices',
        'licenses',
        'certificates',
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
        'licenses',
        'certificates',
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
        if($request->filled('reason')){
            $application->reason=$request->reason;
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
        $application->status=Application::STATUS_SUCCESS;
        if($request->filled('importance_id')){
            $application->importance_id=$request->importance_id;
        }
        $application->save();
        return $application;
    }

    private function commonAll(Request $request)
    {
        $query = QueryBuilder::for(Application::class);
        $query->withoutGlobalScope('permission');
        if ($request->filled('between')) {
            return $query->whereBetween('updated_at', explode(',', $request->between));
               
        }

        return $query;
    }


}
