<?php

namespace App\UseCases;
use Exception;
use App\Models\Compliance;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use Illuminate\Support\Facades\DB;


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
           'files'=>'required'
        ]);

        DB::beginTransaction();
        try{
            $compliance = Compliance::make($request->only( 'from','to', 'definition'));
            $file = $this->service->uploads($request->file('files'));
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
                $file = $this->service->uploads($request->file('files'));
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
