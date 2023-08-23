<?php

namespace App\Http\Controllers\reference;

use App\Models\Staff;
use Illuminate\Http\Request;
use App\UseCases\StaffService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class StaffController extends Controller
{
    private $service;
    public function __construct(StaffService $service)
    {
        $this->service=$service;
    }

    public function index(Request $request)
    {
        $query = QueryBuilder::for(Staff::class);
        if (!empty($request->get('search'))) {
            $query->where(function($q) use ($request){
                $q->where('name', 'like', '%' . $request->get('search') . '%');
                $q->orwhere('phone', 'like', '%' . $request->get('search') . '%');
            });
            
        }

        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }


    public function store(Request $request)
    {
      return $this->service->create($request);
    }


    public function show(Staff $staff)
    {
        return $staff;
    }
    public function update(Request $request, Staff $staff)
    {
       return $this->service->edit($request, $staff);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        return $this->service->remove($staff);
    }
}
