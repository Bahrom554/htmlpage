<?php

namespace App\Http\Controllers\user;


use App\Models\Technique;
use Illuminate\Http\Request;
use App\UseCases\TechniqueService;
use App\Http\Controllers\Controller;
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
