<?php

namespace App\Http\Controllers\user;

use App\Models\Purpose;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class PurposeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query = QueryBuilder::for(Purpose::class);
        if (!empty($request->get('search'))) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
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
        $request->validate([
            'name'=>'required|string',
            
         ]);
         
         return Purpose::create($request->only('name'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Purpose $purpose)
    {
        
        if (!empty($request->include)) {
            $purpose->load(explode(',', $request->include));
        }

        return $purpose;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function edit(Purpose $purpose)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purpose $purpose)
    {
        $request->validate([
            'name'=>'required|string',
            
         ]);
         $purpose->update($request->only('name'));
         return $purpose;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purpose $purpose)
    {
        $purpose->delete();
        return 'deleted';
    }
}
