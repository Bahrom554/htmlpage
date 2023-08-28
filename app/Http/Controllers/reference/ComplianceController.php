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
