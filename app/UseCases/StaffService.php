<?php

namespace App\UseCases;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffService
{
    public function create(Request $request)
    {
        $request->validate([
           'subject_id'=>'required|integer|exists:subjects,id',
           'appointment_order_id'=>'required|integer|exists:appointment_orders,id',
           'diploma_id'=>'nullable|integer|exists:diplomas,id',
           'professional_development_id'=>'nullable|integer|exists:professional_developments,id',
           'complience_id'=>'nullable|integer|exists:compliences,id',
           'name' =>'required|string',
           'position'=>'nullable|string',
           'definition'=>'nullable|string',

        ]);

        $staff = Staff::make($request->only(
        'subject_id',
        'appointment_order_id',
        'diploma_id',
        'professional_development_id',
        'complience_id',
        'name',
        'position',
        'phone',
        'definition'));
        $staff->save();
        return $staff;
    }

    public function edit(Request $request, Staff $staff)
    {
        $request->validate([
           'subject_id'=>'integer|exists:subjects,id',
           'appointment_order_id'=>'integer|exists:appointment_orders,id',
           'diploma_id'=>'nullable|integer|exists:diplomas,id',
           'professional_development_id'=>'nullable|integer|exists:professional_developments,id',
           'complience_id'=>'nullable|integer|exists:compliences,id',
           'name' =>'string',
           'position'=>'nullable|string',
           'definition'=>'nullable|string',
        ]);
        $staff->update($request->only('subject_id',
        'appointment_order_id',
        'diploma_id',
        'professional_development_id',
        'complience_id',
        'name',
        'position',
        'phone',
        'definition'));
        return $staff;

    }
    public function remove(Staff $staff)
    {
        $staff->delete();
        return 'deleted';
    }


}
