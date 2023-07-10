<?php

namespace App\UseCases;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectService
{
    public function create(Request $request)
    {
        $request->validate([
           'name'=>'required|string',
           'address'=>'nullable|string',
           'parent_id'=>'nullable|integer|exists:subjects,id',
           'documents'=>'nullable|array|exists:files,id'
        ]);
        
        $subject = Subject::make($request->only('name', 'address', 'parent_id', 'documents'));
        $subject->save();
        return $subject;
    }

    public function edit(Request $request, Subject $subject)
    {
        $request->validate([
            'name'=>'required|string',
           'address'=>'nullable|string',
           'parent_id'=>'nullable|integer|exists:subjects,id',
           'documents'=>'nullable|array|exists:files,id'
        ]);
        $subject->update($request->only('name', 'address', 'parent_id', 'documents'));
        return $subject;

    }
    public function remove(Subject $subject)
    {
        $subject->delete();
        return 'deleted';
    }


}
