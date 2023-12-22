<?php

namespace App\Http\Controllers\oldversion;


use App\Http\Controllers\Controller;
use App\Models\Technique;
use App\UseCases\TechniqueService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class TechniqueController extends Controller
{
    private $service;
    public function __construct(TechniqueService $service)
    {
        $this->service=$service;
    }

    public function store(Request $request)
    {

        return $this->service->create($request);
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Technique::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Technique $technique)
    {
        return $this->service->edit($request,$technique);
    }

    public function destroy(Technique $technique)
    {
        return $this->service->remove($technique);
    }
}
