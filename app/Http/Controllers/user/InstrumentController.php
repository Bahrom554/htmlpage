<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Models\Instrument;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\UseCases\InstrumentService;

class InstrumentController extends Controller
{
    private $service;
    public function __construct(InstrumentService $service)
    {
        $this->service=$service;
    }

    public function store(Request $request)
    {

        $instrument = $this->service->create($request);

        if (!empty($request->append)) {
            $instrument->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $instrument->load(explode(',', $request->include));
        };

        return $instrument;

    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Instrument::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Instrument $instrument)
    {
        return $this->service->edit($request,$instrument);
    }

    public function destroy($id)
    {
        return $this->service->remove($id);
    }
}
