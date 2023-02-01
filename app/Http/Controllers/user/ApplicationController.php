<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\application\ApplicationCreateRequest;
use App\Http\Requests\application\ApplicationEditRequest;
use App\Models\Application;
use App\Models\User;
use App\UseCases\ApplicationService;
use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use League\Flysystem\Plugin\AbstractPlugin;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;


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
        $query->allowedAppends(!empty($request->append) ? explode(',', $request->get('append')) : []);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        return $query->paginate($request->per_page);

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
        return $this->service->edit($request,$application);
    }

    public function destroy(Application $application)
    {
        return $this->service->remove($application);
    }


    public function reject(Request $request, Application $application)
    {
        return $this->service->reject($request, $application);
    }
    public function register(Request $request, Application $application){

        return $this->service->register($request,$application);
    }
    public function success(Request $request, Application $application){

        return $this->service->success($request,$application);
    }
    public function importance(Request $request, Application $application){

        return $this->service->importance($request,$application);
    }



}
