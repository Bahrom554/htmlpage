<?php

namespace App\Http\Controllers\reference;

use App\Http\Controllers\Controller;
use App\Models\ToolType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ToolTypeController extends Controller
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
            foreach ($filters as $k => $tool) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query = QueryBuilder::for(ToolType::class);
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
        $query = QueryBuilder::for(ToolType::class);
        $tool_type =$query->findOrFail($id);
        if (!empty($request->append)) {
            $tool_type ->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $tool_type ->load(explode(',', $request->include));
        }
        return $tool_type ;
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'category'=>'required|integer|between:1,2',
            'manufacture'=>'required|array',
        ]);
        DB::beginTransaction();
        try{
            $tool_type  =ToolType::create($request->only('name','category'));

            foreach($request->manufacture as $manufacture){
                $tool_type ->manufactures()->create(['name'=>$manufacture]);
            }
            DB::commit();
        }catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

        return $tool_type ;
    }


    public function update(Request $request, ToolType $tool_type )
    {
        $request->validate([
            'name'=>'required|string',
            'category'=>'required|integer|between:1,2'

        ]);

        $tool_type ->update($request->only('name','category'));
        return $tool_type ;
    }


    public function destroy(ToolType $tool_type )
    {
        $tool_type ->delete();
        return 'deleted';
    }
}
