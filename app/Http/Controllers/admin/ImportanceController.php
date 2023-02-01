<?php

namespace App\Http\Controllers\admin;
use App\Models\Importance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class ImportanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = QueryBuilder::for(Importance::class);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedSorts(request()->sort);
        return $query->paginate(10);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated=$request->validate([
           "name"=>"required|string",
           "definition"=>"required|string"

        ]);
        $importance=Importance::create($validated);
        return $importance;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Importance  $importance
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Importance $importance)
    {
        $query=QueryBuilder::for(Importance::class);
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        return $query->where('id',$importance->id)->firstOrFail();
        


        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Importance  $importance
     * @return \Illuminate\Http\Response
     */
    public function edit(Importance $importance)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Importance  $importance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Importance $importance)
    {
        $validated=$request->validate([
            "name"=>"required|string",
            "definition"=>"required|string"
 
         ]);
         $importance->update($validated);
         return $importance;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Importance  $importance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Importance $importance)
    {
        $importance->delete();
        return 'deleted';
    }
}
