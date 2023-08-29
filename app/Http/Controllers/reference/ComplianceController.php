<?php

namespace App\Http\Controllers\reference;


use App\Models\AppointmentOrder;
use App\Models\Compliance;
use Illuminate\Http\Request;
use App\UseCases\ComplianceService;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ComplianceController extends Controller
{
    private $service;
    public function __construct(ComplianceService $service)
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
        $query = QueryBuilder::for(Compliance::class);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }

    public function store(Request $request)
    {

        $compliance= $this->service->create($request);

        if (!empty($request->append)) {
            $compliance->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $compliance->load(explode(',', $request->include));
        };

        return $compliance;
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Compliance::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Compliance $compliance)
    {
        return $this->service->edit($request,$compliance);
    }

    public function destroy($id)
    {
        return $this->service->remove($id);
    }
}
