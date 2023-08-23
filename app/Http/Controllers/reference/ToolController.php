<?php

namespace App\Http\Controllers\reference;

use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ToolController extends Controller
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
        $query = QueryBuilder::for(Tool::class);
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
        $query = QueryBuilder::for(Tool::class);
        $tool=$query->findOrFail($id);
        if (!empty($request->append)) {
            $tool->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $tool->load(explode(',', $request->include));
        }
        return $tool;
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'type'=>'required|integer|between:1,2'
         ]);
         DB::beginTransaction();
         try{
            $tool =Tool::create($request->only('name','type'));
            if($request->manufacture){
               $tool->manufactures()->create(['name'=>$request->manufacture]);
            }
            DB::commit();
         }catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
       
      return $tool;
    }


    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name'=>'required|string',
            'type'=>'required|integer|between:1,2'

         ]);

          $tool->update($request->only('name','type'));
          return $tool;
    }


    public function destroy(Tool $tool)
    {
        $tool->delete();
        return 'deleted';
    }
}
