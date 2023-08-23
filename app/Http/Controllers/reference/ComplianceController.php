<?php

namespace App\Http\Controllers\reference;


use App\Models\Compliance;
use Illuminate\Http\Request;
use App\UseCases\ComplianceService;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class ComplianceController extends Controller
{
    private $service;
    public function __construct(ComplianceService $service)
    {
        $this->service=$service;
    }

    public function store(Request $request)
    {

        return $this->service->create($request);
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
