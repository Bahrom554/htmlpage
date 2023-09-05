<?php

namespace App\Http\Controllers\user;

use App\Models\Tool;
use Illuminate\Http\Request;
use App\Models\InternetProvider;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\UseCases\InternetProviderService;

class InternetProviderController extends Controller
{
    private $service;
    public function __construct(InternetProviderService $service)
    {
        $this->service=$service;
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
        $query = QueryBuilder::for(InternetProvider::class);

        if (!empty($request->get('search'))) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }

    public function store(Request $request)
    {

        $internet_provider = $this->service->create($request);

        if (!empty($request->append)) {
            $internet_provider->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $internet_provider->load(explode(',', $request->include));
        };

        return $internet_provider;

    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(InternetProvider::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, InternetProvider $internet_provider)
    {
        return $this->service->edit($request,$internet_provider);
    }

    public function destroy(InternetProvider $internetProvider)
    {
        return $this->service->remove($internetProvider);
    }
}
