<?php

namespace App\Http\Controllers\reference;


use Illuminate\Http\Request;
use App\Models\Manufacture;
use App\Http\Controllers\Controller;

class ManufactureController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin|manager')->only('store', 'update', 'destroy');
    }

    public function index(Request $request)
    {
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $manufacture) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query = QueryBuilder::for(Manufacture::class);
        if (!empty($request->get('search'))) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }

        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);

        $query->allowedFilters($filter);
        $query->orderBy('updated_at', 'desc');
        return $query->get();
    }


    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Manufacture::class);
        $manufacture=$query->findOrFail($id);
        if (!empty($request->append)) {
            $manufacture->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $manufacture->load(explode(',', $request->include));
        }
        return $manufacture;
    }


    public function store(Request $request)
    {
        $request->validate([
            'tool_id'=>'required|integer|exists:tools,id',
            'name'=>'required|string',
            'definition'=>'nullable|string'
         ]);
          $manufacture =Manufacture::create($request->only('name','definition'));
          $manufacture->tools()->attach($request->tool_id);

    }


    public function update(Request $request, Manufacture $manufacture)
    {
        $request->validate([
            'name'=>'string',
            'definition'=>'nullable|string'

         ]);

         return $manufacture->update($request->only('name','definition'));
    }


    public function destroy(Manufacture $manufacture)
    {
        $manufacture->delete();
        return 'deleted';
    }
}
