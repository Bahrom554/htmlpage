<?php

namespace App\Http\Controllers\user;

use App\Models\SubjectType;
use Illuminate\Http\Request;
use App\Models\Instrument;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\UseCases\InstrumentService;

class InstrumentController extends Controller
{
    private $service;
    public function __construct(InstrumentService $service)
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
        $query = QueryBuilder::for(Instrument::class);

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

        $instrument = $this->service->create($request);

        if (!empty($request->append)) {
            $instrument->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $instrument->load(explode(',', $request->include));
        };

        return $instrument;

    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Instrument::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Instrument $instrument)
    {
        return $this->service->edit($request,$instrument);
    }

    public function destroy($id)
    {
        return $this->service->remove($id);
    }
}
