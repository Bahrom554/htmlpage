<?php

namespace App\UseCases;
use Illuminate\Http\Request;
use App\Models\AppointmentOrder;

class AppointmentOrderService
{
    public function create(Request $request)
    {
        $request->validate([
           'definition'=>'nullable|string',
           'date'=>'required|date|before:now',
           'file_id'=>'required|integer|exists:files,id'
        ]);
        
        $appointment_order = AppointmentOrder::make($request->only('definition', 'date', 'file_id'));
        $appointment_order->save();
        return $appointment_order;
    }

    public function edit(Request $request, AppointmentOrder $appointment_order)
    {
        $request->validate([
            'definition'=>'nullable|string',
            'date'=>'date|before:now',
            'file_id'=>'integer|exists:files,id'
        ]);
        $appointment_order->update($request->only('definition', 'date', 'file_id'));
        return $appointment_order;

    }
    public function remove(AppointmentOrder $appointment_order)
    {
        $appointment_order->delete();
        return 'deleted';
    }


}
