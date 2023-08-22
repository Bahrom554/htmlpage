<?php

namespace App\UseCases;
use Illuminate\Http\Request;
use App\Models\ProfessionalDevelopment;

class ProfessionalDevelopmentService
{
    public function create(Request $request)
    {
        $request->validate([
           'definition'=>'nullable|string',
           'date'=>'required|date|before:now',
           'file_id'=>'required|integer|exists:files,id'
        ]);
        
        $professional_development = ProfessionalDevelopment::make($request->only('definition', 'date', 'file_id'));
        $professional_development->save();
        return $professional_development;
    }

    public function edit(Request $request, ProfessionalDevelopment $professional_development)
    {
        $request->validate([
            'definition'=>'nullable|string',
            'date'=>'date|before:now',
            'file_id'=>'integer|exists:files,id'
        ]);
        $professional_development->update($request->only('definition', 'date', 'file_id'));
        return $professional_development;

    }
    public function remove(ProfessionalDevelopment $professional_development)
    {
        $professional_development->delete();
        return 'deleted';
    }


}
