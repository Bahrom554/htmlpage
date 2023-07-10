<?php

namespace App\Http\Controllers\user;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Staff::class);
        if (!empty($request->get('search'))) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }
        
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }

  
    public function store(Request $request)
    {
       $validated=$request->validate([

            'name'=>'required|string',
            'phone'=>'required|string',
            'statue'=>'nullable|string',
            'definition'=>'nullable|string'
        ]);
        $staff=Staff::make($validated);
        $staff->user_id=Auth::user()->id;
        $staff->save();
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
        $staff->update($request->only([ 'name','phone','statue','definition']));
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
