<?php

namespace App\Http\Controllers\user;

use App\Models\InternetProvider;
use App\Models\Provider;
use Illuminate\Http\Request;
use App\Models\Network;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\UseCases\NetworkService;

class NetworkController extends Controller
{
    private $service;
    public function __construct(NetworkService $service)
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
        $query = QueryBuilder::for(Network::class);

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

        $network = $this->service->create($request);

        if (!empty($request->append)) {
            $network->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $network->load(explode(',', $request->include));
        };

        return $network;

    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Network::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Network $network)
    {
        return $this->service->edit($request,$network);
    }

    public function destroy($id)
    {
        return $this->service->remove($id);
    }


}
