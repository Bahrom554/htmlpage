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
        $all_by_mont=$this->commonAll($request)->selectRaw('year(updated_at) year, monthname(updated_at) month, count(*) total')->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->get();
        $by_status = $this->commonAll($request)->groupBy('status')->selectRaw('count(*) as total, status')->get();
        $by_cert = $this->commonAll($request)->whereNotNull('certificates')->selectRaw('year(updated_at) year, monthname(updated_at) month, count(*) total')->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->get();
        $by_lic = $this->commonAll($request)->whereNotNull('licenses')->selectRaw('year(updated_at) year, monthname(updated_at) month, count(*) total')->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->get();
        $by_sub = $this->commonAll($request)->leftJoin('users', 'applications.user_id', 'users.id')->groupBy('subject')->selectRaw('count(*) as total, subject')->get()->sortByDesc('total');
        $by_dev = $this->commonAll($request)->leftJoin('devices', 'applications.device_id', 'devices.id')->groupBy('devices.id')->selectRaw('count(*) as total, devices.*')->get()->sortByDesc('total');
        $responce = [
            "applications" => $all,
            "allInMont"=>$all_by_mont,
            "certificate" => $by_cert,
            "license" => $by_lic,
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
            'definition',
            'certificates',
            'licenses',
            'device_id',
            'error_or_broken',
            'telecommunication_network',
            'provide_cyber_security',
            'threats_to_information_security',
            'consequences_of_an_incident',
            'organizational_and_technical_measures_to_ensure_security'
        ));
        $app->user_id = Auth::user()->id;
        $app->save();
        return $app;
    }

    public function edit(ApplicationEditRequest $request, Application $application)
    {
        $application->update($request->only([
            'name',
            'definition',
            'certificates',
            'licenses',
            'device_id',
            'error_or_broken',
            'telecommunication_network',
            'provide_cyber_security',
            'threats_to_information_security',
            'consequences_of_an_incident',
            'organizational_and_technical_measures_to_ensure_security',
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
            'definition' => 'nullable|string'
        ]);
        $application->status=Application::STATUS_REJECT;
        if($request->filled('definition')){
            $application->definition=$request->definition;
        }
        $application->save();
        return $application;
    }
    public function register(Request $request, Application $application){
        $request->validate([
            'definition' => 'nullable|string'
        ]);
        $application->status=Application::STATUS_PROCESS;
        if($request->filled('definition')){
            $application->definition=$request->definition;
        }
        $application->save();
        return $application;
    }
    public function success(Request $request, Application $application){
        $request->validate([
            'definition' => 'nullable|string'
        ]);
        $application->status=Application::STATUS_SUCCESS;
        if($request->filled('definition')){
            $application->definition=$request->definition;
        }
        $application->save();
        return $application;
    }

    private function commonAll(Request $request)
    {
        $query = QueryBuilder::for(Application::class);
        $query->withoutGlobalScope('permission');
        if ($request->filled('between')) {
            return $query->whereBetween('applications.updated_at', explode(',', $request->between));
               
        }

        return $query;
    }


}
