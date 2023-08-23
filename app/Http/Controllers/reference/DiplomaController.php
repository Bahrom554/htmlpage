<?php

namespace App\Http\Controllers\reference;


use App\Models\Diploma;
use Illuminate\Http\Request;
use App\UseCases\DiplomaService;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class DiplomaController extends Controller
{ private $service;
    public function __construct(DiplomaService $service)
    {
        $this->service=$service;
    }

    public function store(Request $request)
    {

        return $this->service->create($request);
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
