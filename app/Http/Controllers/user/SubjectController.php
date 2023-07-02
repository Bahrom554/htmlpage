<?php

namespace App\Http\Controllers\user;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\UseCases\SubjectService;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;

class SubjectController extends Controller
{
    private $service;
    public function __construct(SubjectService $service)
    {
        $this->service=$service;
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
