<?php

namespace App\UseCases;
use App\Models\Diploma;
use App\Models\Files;
use Exception;
use DomainException;
use App\Models\Compliance;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use Illuminate\Support\Facades\DB;


class ComplianceService
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
           'from'=>'required|date|before:now',
           'to'=>'required|date|after:now',
           'files'=>'required'
        ]);

        DB::beginTransaction();
        try{
            $compliance = Compliance::make($request->only( 'from','to', 'definition'));
            $file = $this->fileService->uploads($request->file('files'));
            $compliance->file_id = $file->id;
            $compliance->save();
            DB::commit();
            return $compliance;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }


    }

    public function edit(Request $request, Compliance $compliance)
    {
        $request->validate([
            'definition'=>'nullable|string',
           'from'=>'date|before:now',
           'to'=>'date|after:now',
           'files'=>'nullable'
        ]);
        try{
            $compliance->definition =$request->definition;
            $compliance->from = $request->from;
            $compliance->to = $request->to;
            if($request->file('files')){
                $file = $this->fileService->uploads($request->file('files'));
                $compliance->file_id = $file->id;
            }
            $compliance->save();
            DB::commit();
            return $compliance;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }

    }
    public function remove(Compliance $compliance)
    {
        try{

            if($file = Files::find($compliance->file_id)){
                $this->fileService->delete($file);
            }
            $compliance->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }
    }


}
