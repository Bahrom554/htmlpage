<?php

namespace App\UseCases;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentService
{
    public function create(Request $request)
    {
        $request->validate([
           'application_id'=>'required|integer|exists:applications,id',
           'description'=>'required|string',
           'column_id'=>'required|string',
           'version'=>'required|string',
           'documents'=>'nullable|array|exists:files,id'
        ]);
        
        $comment = Comment::make($request->only('name', 'manufacturer', 'model', 'version', 'documents'));
        $comment->save();
        return $comment;
    }

    public function edit(Request $request, Comment $comment)
    {
        $request->validate([
            'name'=>'required|string',
            'manufacturer'=>'required|string',
            'model'=>'required|string',
            'version'=>'required|string',
            'documents'=>'nullable|array|exists:files,id'
        ]);
        $comment->update($request->only('name', 'manufacturer', 'model', 'version', 'documents'));
        return $comment;

    }
    public function remove(Comment $comment)
    {
        $comment->delete();
        return 'deleted';
    }


}
