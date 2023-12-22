<?php

namespace App\Http\Controllers\user;

use Carbon\Carbon;
use App\Models\User;
use DomainException;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\UseCases\ApplicationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $this->middleware('role:user|manager')->only('store', 'update', 'destroy');
        $this->service = $service;
    }
    public function dash(Request $request)
    {

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
        $app = $query->findOrFail($id);
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
        if (!$application->userActions()) {
            abort(403);
        }
        return $this->service->edit($request, $application);
    }
    public function destroy(Application $application)
    {
        if (!$application->userActions()) {
            abort(403);
        }
        return $this->service->remove($application);
    }


    public function reject(Application $application)
    {
        if ($application->adminActions() || $application->managerActions()) {
            $application->status = Application::STATUS_REJECT;
            $application->save();
        }
        return $application;
    }

    public function success(Application $application)
    {
        if ($application->adminActions()) {
            $application->status = Application::STATUS_SUCCESS;
            $application->save();
        } elseif ($application->managerActions()) {
            $application->status = Application::STATUS_MANAGER_TO_ADMIN;
            $application->save();
        }

        return $application;
    }

    public function comment(Request $request, Application $application)
    {

        $request->validate([
            'column_*' => 'nullable|string'
        ]);
        if ($application->adminActions() || $application->managerActions()) {
            return $this->service->writeComment($request,$application);
        }
        else{
            abort(403);
        }


       
    }

    public function report(Request $request){
        return $this->service->search($request);
    }
}
