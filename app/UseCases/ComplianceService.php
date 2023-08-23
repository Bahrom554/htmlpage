<?php

namespace App\UseCases;
use Exception;
use App\Models\Compliance;
use Illuminate\Http\Request;
use App\UseCases\FileService;

class ComplianceService
{
    private $service;

    public function __construct( FileService $service)
    {
        $this->service=$service;

    }
    
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
    public function remove($id)
    {
        try{
            $compliance =Compliance::findOrFail($id);
            // $this->service->delete($compliance->file_id);
            $compliance->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }
    }


}
