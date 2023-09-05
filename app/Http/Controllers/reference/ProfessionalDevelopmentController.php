<?php

namespace App\Http\Controllers\reference;


use App\Models\Compliance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\ProfessionalDevelopment;
use App\UseCases\ProfessionalDevelopmentService;

class ProfessionalDevelopmentController extends Controller
{
    private $service;
    public function __construct(ProfessionalDevelopmentService $service)
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
        $query = QueryBuilder::for(ProfessionalDevelopment::class);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }


    public function store(Request $request)
    {

       $model= $this->service->create($request);

        if (!empty($request->append)) {
            $model->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $model->load(explode(',', $request->include));
        };

        return $model;
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(ProfessionalDevelopment::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, ProfessionalDevelopment $professional_development)
    {
        return $this->service->edit($request,$professional_development);
    }

    public function destroy(ProfessionalDevelopment $development)
    {
        return $this->service->remove($development);
    }
}
