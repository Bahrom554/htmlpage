<?php

namespace App\UseCases;
use Illuminate\Http\Request;
use App\Models\Compliance;

class ComplianceService
{
    public function create(Request $request)
    {
        $request->validate([
           'definition'=>'nullable|string',
           'from'=>'required|date|before:now',
           'to'=>'required|date|after:now',
           'file_id'=>'required|integer|exists:files,id'
        ]);
        
        $compliance = Compliance::make($request->only('file_id', 'from','to', 'definition'));
        $compliance->save();
        return $compliance;
    }

    public function edit(Request $request, Compliance $compliance)
    {
        $request->validate([
            'definition'=>'nullable|string',
           'from'=>'date|before:now',
           'to'=>'date|after:now',
           'file_id'=>'integer|exists:files,id'
        ]);
        $compliance->update($request->only('file_id', 'from','to', 'definition'));
        return $compliance;

    }
    public function remove(Compliance $compliance)
    {
        $compliance->delete();
        return 'deleted';
    }


}
