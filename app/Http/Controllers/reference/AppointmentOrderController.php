<?php

namespace App\Http\Controllers\reference;


use Illuminate\Http\Request;
use App\Models\AppointmentOrder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use App\UseCases\AppointmentOrderService;

class AppointmentOrderController extends Controller
{

    private $service;
    public function __construct(AppointmentOrderService $service)
    {
        $this->service=$service;
    }

    public function store(Request $request)
    {

        return $this->service->create($request);
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(AppointmentOrder::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, AppointmentOrder $appointment_order)
    {
        return $this->service->edit($request,$appointment_order);
    }

    public function destroy(AppointmentOrder $appointment_order)
    {
        return $this->service->remove($appointment_order);
    }
}
