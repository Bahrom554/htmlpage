<?php

namespace App\Http\Controllers\reference;


use App\Models\Staff;
use Illuminate\Http\Request;
use App\Models\AppointmentOrder;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\UseCases\AppointmentOrderService;

class AppointmentOrderController extends Controller
{

    private $service;
    public function __construct(AppointmentOrderService $service)
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
        $query = QueryBuilder::for(AppointmentOrder::class);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }

    public function store(Request $request)
    {

        $appointment_order = $this->service->create($request);

        if (!empty($request->append)) {
            $appointment_order->append(explode(',', $request->append));
        };
        if (!empty($request->include)) {
            $appointment_order->load(explode(',', $request->include));
        };

        return $appointment_order;

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

    public function destroy(AppointmentOrder $appointmentOrder)
    {
        return $this->service->remove($appointmentOrder);
    }
}
