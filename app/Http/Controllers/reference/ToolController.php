<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\UseCases\ToolService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ToolController extends Controller
{
    private $service;
    public function __construct(ToolService $service)
    {
        $this->service=$service;
        $this->middleware('role:admin|manager')->only('store', 'update', 'destroy');
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
        $query = QueryBuilder::for(Tool::class);

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

        $tool  = $this->service->create($request);

        if (!empty($request->append)) {
            $tool ->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $tool ->load(explode(',', $request->include));
        };

        return $tool ;

    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Tool::class);
        $tool=$query->findOrFail($id);
        if (!empty($request->append)) {
            $tool->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $tool->load(explode(',', $request->include));
        }
        return $tool;
    }

    public function update(Request $request, Tool $tool )
    {
        return $this->service->edit($request,$tool );
    }

    public function destroy(Tool $tool)
    {
        return $this->service->remove($tool);
    }
}
