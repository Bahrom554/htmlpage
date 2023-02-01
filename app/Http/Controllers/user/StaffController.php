<?php

namespace App\Http\Controllers\user;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
  

  
    public function store(Request $request)
    {
       $validated=$request->validate([

            'name'=>'required|string',
            'phone'=>'required|string',
            'statue'=>'nullable|string',
            'definition'=>'nullable|string'
        ]);
        $staff=Staff::create($validated);
        return $staff;
    }

  
    public function show(Staff $staff)
    {
        return $staff;
    }
    public function update(Request $request, Staff $staff)
    {
        $validated=$request->validate([

            'name'=>'required|string',
            'phone'=>'required|string',
            'statue'=>'nullable|string',
            'definition'=>'nullable|string'
        ]);
        $staff->update($validated);
        return $staff;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return 'deleted';
    }
}
