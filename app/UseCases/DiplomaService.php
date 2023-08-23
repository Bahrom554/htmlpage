<?php

namespace App\UseCases;
use Exception;
use App\Models\Diploma;
use Illuminate\Http\Request;
use App\UseCases\FileService;

class DiplomaService
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
           'educational_institution'=>'required|string',
           'degree'=>'required|integer',
           'file_id'=>'required|integer|exists:files,id'
        ]);
        
        $diploma = Diploma::make($request->only('file_id', 'educational_institution', 'degree','definition'));
        $diploma->save();
        return $diploma;
    }

    public function edit(Request $request, Diploma $diploma)
    {
        $request->validate([
            'definition'=>'nullable|string',
            'educational_institution'=>'string',
            'degree'=>'integer',
            'file_id'=>'integer|exists:files,id'
        ]);
        $diploma->update($request->only('file_id', 'educational_institution', 'degree','definition'));
        return $diploma;

    }
    public function remove($id)
    {
        try{
            $diploma = Diploma::findOrFail($id);
            // $this->service->delete($diploma->file_id);
            $diploma->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }
    }


}
