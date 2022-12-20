<?php

namespace App\UseCases;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceService
{
    public function create(Request $request)
    {
        $request->validate([
            'ram'=>'nullable|array',
            'hdd'=>'nullable|array',
            'ssd'=>'nullable|array',
            'cpu'=>'nullable|array',
            'architecture'=>'nullable|array',
            'power'=>'nullable|array',
            'os'=>'nullable|array',
            'version'=>'nullable|array',
            'case'=>'nullable|array',
            'type'=>'nullable|array',
            'slot'=>'nullable|array',
            'definition'=>'nullable|string'
        ]);
        $device = Device::make($request->only('ram', 'hdd', 'ssd', 'cpu', 'architecture', 'power', 'os', 'version', 'case', 'type', 'slot', 'definition'));
        $device->save();
        return $device;
    }

    public function edit(Request $request, Device $device)
    {
        $request->validate([
            'ram'=>'nullable|array',
            'hdd'=>'nullable|array',
            'ssd'=>'nullable|array',
            'cpu'=>'nullable|array',
            'architecture'=>'nullable|array',
            'power'=>'nullable|array',
            'os'=>'nullable|array',
            'version'=>'nullable|array',
            'case'=>'nullable|array',
            'type'=>'nullable|array',
            'slot'=>'nullable|array',
            'definition'=>'nullable|string'
        ]);
        $device->update($request->only('ram', 'hdd', 'ssd', 'cpu', 'architecture', 'power', 'os', 'version', 'case', 'type', 'slot', 'definition'));
        return $device;

    }
    public function remove(Device $device)
    {
        $device->delete();
        return 'deleted';
    }


}
