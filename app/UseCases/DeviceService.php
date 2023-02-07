<?php

namespace App\UseCases;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceService
{
    public function create(Request $request)
    {
        $request->validate([
           'name'=>'required|string',
           'manufacturer'=>'required|string',
           'model'=>'required|string',
           'version'=>'required|string',
           'documents'=>'nullable|array|exists:files,id'
        ]);
        
        $device = Device::make($request->only('name', 'manufacturer', 'model', 'version', 'documents'));
        $device->save();
        return $device;
    }

    public function edit(Request $request, Device $device)
    {
        $request->validate([
            'name'=>'required|string',
            'manufacturer'=>'required|string',
            'model'=>'required|string',
            'version'=>'required|string',
            'documents'=>'nullable|array|exists:files,id'
        ]);
        $device->update($request->only('name', 'manufacturer', 'model', 'version', 'documents'));
        return $device;

    }
    public function remove(Device $device)
    {
        $device->delete();
        return 'deleted';
    }


}
