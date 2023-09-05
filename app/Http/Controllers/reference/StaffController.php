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
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
       $query = $this->service->search($request, true);

        if (!empty($request->get('search'))) {
            $query->where(function($q) use ($request){
                $q->where('name', 'like', '%' . $request->get('search') . '%');
                $q->orwhere('phone', 'like', '%' . $request->get('search') . '%');
            });

        }

        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->with('appointment');
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }


    public function store(Request $request)
    {
      return $this->service->create($request);
    }


    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Staff::class);
        $staff=$query->findOrFail($id);
        if (!empty($request->append)) {
            $staff->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $staff->load(explode(',', $request->include));
        }
        return $staff;
    }
    public function update(Request $request, Staff $staff)
    {
       return $this->service->edit($request, $staff);
    }

    public function destroy(Staff $staff)
    {
        return $this->service->remove($staff);
    }
}
