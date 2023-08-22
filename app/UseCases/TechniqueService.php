<?php

namespace App\UseCases;
use App\Models\Technique;
use Illuminate\Http\Request;

class TechniqueService
{
    public function create(Request $request)
    {
        $request->validate([
           'name'=>'required|string',
           'manufacturer'=>'required|string',
           'model'=>'required|string',
           'version'=>'nullable|string',
           'documents'=>'nullable|array|exists:files,id'
        ]);
        
        $technique = Technique::make($request->only('name', 'manufacturer', 'model', 'version', 'documents'));
        $technique->save();
        return $technique;
    }

    public function edit(Request $request, Technique $technique)
    {
        $request->validate([
            'name'=>'required|string',
            'manufacturer'=>'required|string',
            'model'=>'required|string',
            'version'=>'nullable|string',
            'documents'=>'nullable|array|exists:files,id'
        ]);
        $technique->update($request->only('name', 'manufacturer', 'model', 'version', 'documents'));
        return $technique;

    }
    public function remove(Technique $technique)
    {
        $technique->delete();
        return 'deleted';
    }


}
