<?php

namespace App\Http\Controllers\reference;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {

        return $this->service->create($request);
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

    public function destroy($id)
    {
        return $this->service->remove($id);
    }
}
