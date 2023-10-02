<?php

namespace App\Http\Controllers\reference;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\UseCases\SubjectService;
use App\Http\Controllers\Controller;
use App\Http\Requests\subject\SubjectCreateRequest;
use App\Http\Requests\subject\SubjectEditRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class SubjectController extends Controller
{
    private $service;
    public function __construct(SubjectService $service)
    {
        $this->middleware('role:admin|manager')->only('store', 'update', 'destroy');

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
            $query->where(function($q) use ($request){
                $q->where('name', 'like', '%' . $request->get('search') . '%');
                $q->orwhere('address_legal',  'like', '%' . $request->get('search') . '%');
                $q->orwhere('address_fact','like', '%' . $request->get('search') . '%');
                $q->orwhere('email','like', '%' . $request->get('search') . '%');
                $q->orwhere('phone','like', '%' . $request->get('search') . '%');
            });


        }
        $query->allowedIncludes(!empty($request->include) ? explode(',', $request->get('include')) : []);
        $query->allowedFilters($filter);
        $query->allowedSorts($request->sort);
        $query->orderBy('updated_at', 'desc');
        return $query->paginate(30);
    }

    public function store(SubjectCreateRequest $request)
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

    public function update(SubjectEditRequest $request, Subject $subject)
    {
        return $this->service->edit($request,$subject);
    }

    public function destroy(Subject $subject)
    {
        return $this->service->remove($subject);
    }
}
