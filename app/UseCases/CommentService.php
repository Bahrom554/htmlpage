<?php

namespace App\UseCases;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function create($data)
    {
        
    $comment = Comment::create($data);
        
        return $comment;
    }

    public function edit(Request $request, Comment $comment)
    {
        
        $request->validate([
            'application_id'=>'required|integer|exists:applications,id',
            'description'=>'required|string',
            'column_id'=>'required|string',
        ]);
        if(Auth::user()->id==$comment->author){
            $comment->update($request->only('application_id', 'description', 'column_id'));
         }
        return $comment;

    }
    public function remove(Comment $comment)
    {
        if(Auth::user()->id==$comment->author){
            $comment->delete();
         }
        return 'deleted';
    }


}
