<?php

namespace App\UseCases;
use App\Models\Files;
use Exception;
use DomainException;
use App\Models\Diploma;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use Illuminate\Support\Facades\DB;


class DiplomaService
{
    private $fileService;

    public function __construct( FileService $fileService)
    {
        $this->fileService=$fileService;

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
    public function remove(Diploma $diploma)
    {
        try{

            if($file = Files::find($diploma->file_id)){
                $this->fileService->delete($file);
            }
            $diploma->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }
    }


}
