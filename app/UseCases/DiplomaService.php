<?php

namespace App\UseCases;
use Exception;
use DomainException;
use App\Models\Diploma;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use Illuminate\Support\Facades\DB;


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
               'files'=>'required'
        ]);
        DB::beginTransaction();
        try{
        $diploma = Diploma::make($request->only( 'educational_institution', 'degree','definition'));
        $file = $this->service->uploads($request->file('files'));
        $diploma->file_id = $file->id;
        $diploma->save();
        DB::commit();
        return $diploma;
        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }


    }

    public function edit(Request $request, Diploma $diploma)
    {
        $request->validate([
            'definition'=>'nullable|string',
            'educational_institution'=>'string',
            'degree'=>'integer',
            'files'=>'nullable'
        ]);

        DB::beginTransaction();
        try{
            $diploma->educational_institution = $request->educational_institution;
            $diploma->degree = $request->degree;
            $diploma->definition = $diploma->definition;
            if($request->file('files')){
                $file = $this->service->uploads($request->file('files'));
                $diploma->file_id = $file->id;
            }
            $diploma->save();
            DB::commit();
            return $diploma;
        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }

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
