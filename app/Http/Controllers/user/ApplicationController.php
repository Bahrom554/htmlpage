<?php

namespace App\Http\Controllers\user;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\UseCases\ApplicationService;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use League\Flysystem\Plugin\AbstractPlugin;
use App\Http\Requests\application\ApplicationEditRequest;
use App\Http\Requests\application\ApplicationCreateRequest;


class ApplicationController extends Controller
{
    private $service;
    public function __construct(ApplicationService $service)
    {
        $this->service=$service;

    }
    public function dash(Request $request){

        return $this->service->dash($request);

    }
    public function index(Request $request)
    {
        return $this->service->list($request);

    }
    public function store(ApplicationCreateRequest $request)
    {
        return $this->service->create($request);
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Application::class);
        $app=$query->findOrFail($id);
        if (!empty($request->append)) {
            $app->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $app->load(explode(',', $request->include));
        }
        return $app;

    }
    public function update(ApplicationEditRequest $request, Application $application)
    {
        if(!$application->userActions()){
            abort(403);
        }
        return $this->service->edit($request,$application);
    }
    public function destroy(Application $application)
    {
        if(!$application->userActions()){
            abort(403);
        }
        return $this->service->remove($application);
    }


    public function reject(Request $request, Application $application)
    {
        if(!($application->managerActions() || $application->adminActions())){
            abort(403);
        }
        return $this->service->reject($request, $application);
    }
    public function writeComment(Request $request, Application $application){

        return $this->service->register($request,$application);
    }
    public function success(Request $request, Application $application){
        if(!$application->adminActions()){
            abort(403);
        }
        return $this->service->success($request,$application);
    }
   




}
