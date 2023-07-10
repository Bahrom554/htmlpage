<?php

namespace App\Http\Controllers\user;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\UseCases\SubjectService;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class SubjectController extends Controller
{
    private $service;
    public function __construct(SubjectService $service)
    {
        $this->service=$service;
    }

    public function index(Request $request){
        $filters = $request->get('filter');
        $filter = [];
        if (!empty($filters)) {
            foreach ($filters as $k => $item) {
                $filter[] = AllowedFilter::exact($k);
            }
        }
        $query = QueryBuilder::for(Subject::class);
        if (!empty($request->get('search'))) {
            $query->where('name', 'like', '%' . $request->get('search') . '%');
        }
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->whereNull('parent_id');
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }

    public function store(Request $request)
    {
        
        return $this->service->create($request);
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Subject::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Subject $subject)
    {
        return $this->service->edit($request,$subject);
    }

    public function destroy(Subject $subject)
    {
        return $this->service->remove($subject);
    }
}
