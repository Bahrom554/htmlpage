<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\UseCases\CommentService;
use Spatie\QueryBuilder\QueryBuilder;

class CommentController extends Controller
{
    private $service;
    public function __construct(CommentService $service)
    {
        $this->service=$service;
    }

    public function store(Request $request)
    {
        
        return $this->service->create($request);
    }
    public function show(Request $request, $id)
    {
        $query = QueryBuilder::for(Comment::class);
        $task=$query->findOrFail($id);
        if (!empty($request->append)) {
            $task->append(explode(',', $request->append));
        }
        if (!empty($request->include)) {
            $task->load(explode(',', $request->include));
        }
        return $task;
    }

    public function update(Request $request, Comment $comment)
    {
        return $this->service->edit($request,$comment);
    }

    public function destroy(Comment $comment)
    {
        return $this->service->remove($comment);
    }
}
