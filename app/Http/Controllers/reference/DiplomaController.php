<?php

namespace App\Http\Controllers\reference;


use App\Models\Compliance;
use App\Models\Diploma;
use Illuminate\Http\Request;
use App\UseCases\DiplomaService;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DiplomaController extends Controller
{ private $service;
    public function __construct(DiplomaService $service)
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
        $query = QueryBuilder::for(Diploma::class);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }

    public function store(Request $request)
    {

       $diploma = $this->service->create($request);

        if (!empty($request->append)) {
            $diploma->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $diploma->load(explode(',', $request->include));
        };

        return $diploma;
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Diploma::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Diploma $diploma)
    {
        return $this->service->edit($request,$diploma);
    }

    public function destroy($id)
    {
        return $this->service->remove($id);
    }
}
