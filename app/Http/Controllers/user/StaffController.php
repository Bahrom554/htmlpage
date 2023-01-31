<?php

namespace App\Http\Controllers;

use App\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
  

  
    public function store(Request $request)
    {
        $request->validate([

            'name'=>'nullable|string',
            'phone'=>'nullable|string',
            'statue'=>'nullable|string',
            'definition'=>'nullable|string'
        ]);
        $staff=Staff::create($request->validated());
        return $staff;
    }

  
    public function show(Staff $staff)
    {
        return $stuff;
    }
    public function update(Request $request, Staff $staff)
    {
        $request->validate([

            'name'=>'nullable|string',
            'phone'=>'nullable|string',
            'statue'=>'nullable|string',
            'definition'=>'nullable|string'
        ]);
        $staff->update($request->validated());
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
