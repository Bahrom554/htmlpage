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
           'type'=>'required|string',
           'definition'=>'nullable|string',
           'documents'=>'nullable|array|exists:files,id'
        ]);
        
        $subject = Subject::make($request->only('name', 'type', 'definition', 'documents'));
        $subject->save();
        return $subject;
    }

    public function edit(Request $request, Subject $subject)
    {
        $request->validate([
            'name'=>'required|string',
            'type'=>'required|string',
            'definition'=>'nullable|string',
            'documents'=>'nullable|array|exists:files,id'
        ]);
        $subject->update($request->only('name', 'type', 'definition', 'documents'));
        return $subject;

    }
    public function remove(Subject $subject)
    {
        $subject->delete();
        return 'deleted';
    }


}
