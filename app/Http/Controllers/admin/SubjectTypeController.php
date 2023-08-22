<?php

namespace App\Http\Controllers\admin;


use App\Models\SubjectType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class SubjectTypeController extends Controller
{
   
  
    public function index(Request $request)
    {
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query = QueryBuilder::for(SubjectType::class);
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
            'name'=>'required|string|unique:subject_types'
            
         ]);
         
         return SubjectType::create($request->only('name'));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,SubjectType $subject_type)
    {
        
        if (!empty($request->include)) {
            $subject_type->load(explode(',', $request->include));
        }

        return $subject_type;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubjectType $subject_type)
    {
        $request->validate([
            'name'=>'required|unique:subject_types,name,'.$subject_type->id,
            
         ]);
         $subject_type->update($request->only('name'));
         return $subject_type;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purpose  $purpose
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubjectType $subject_type)
    {
        $subject_type->delete();
        return 'deleted';
    }
}
