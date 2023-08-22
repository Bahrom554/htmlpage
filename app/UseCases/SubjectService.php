<?php

namespace App\UseCases;

use App\Models\Subject;
use App\Http\Requests\subject\SubjectCreateRequest;
use App\Http\Requests\subject\SubjectEditRequest;

class SubjectService
{
    public function create(SubjectCreateRequest $request)
    {
       
        
        $subject = Subject::make($request->only('name', 'address_legal','address_fact', 'subject_type_id', 'documents'));
        $subject->save();
        return $subject;
    }

    public function edit(SubjectEditRequest $request, Subject $subject)
    {
       
        $subject->update($request->only('name', 'address_legal','address_fact', 'subject_type_id', 'documents'));
        return $subject;

    }
    public function remove(Subject $subject)
    {
        $subject->delete();
        return 'deleted';
    }


}
