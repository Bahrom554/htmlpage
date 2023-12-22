<?php

namespace App\Http\Controllers\oldversion;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\UseCases\DeviceService;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DeviceController extends Controller
{
    private $service;
    public function __construct(DeviceService $service)
    {
        $this->service=$service;
    }

    public function store(Request $request)
    {

        return $this->service->create($request);
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Device::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Device $device)
    {
        return $this->service->edit($request,$device);
    }

    public function destroy(Device $device)
    {
        return $this->service->remove($device);
    }
}
