<?php

namespace App\UseCases;
use DomainException;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Models\Instrument;
use Illuminate\Support\Facades\DB;

use Exception;

class InstrumentService
{

    private $service;

    public function __construct( FileService $service)
    {
        $this->service=$service;

    }
    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'type'=>'required|integer',
            'tool_id'=>'required|integer|exists:tools,id',
            'manufacture_id'=>'required|integer|exists:manufactures,id',
            'from'=>'nullable|date',
            'to'=>'nullable|date',
            'definition'=>'nullable|string',
            'files'=>'required'
        ]);
        DB::beginTransaction();

        try{

            $instrument = Instrument::make($request->only('name', 'type','tool_id','manufacture_id','from','to','definition'));
            $file = $this->service->uploads($request->file('files'));
            $instrument->file_id = $file->id;
            $instrument->save();
            DB::commit();
            return $instrument;

        }catch (\Exception $e) {
            DB::rollBack();
            return $e;
            // throw new DomainException($e->getMessage(), $e->getCode());
        }



    }

    public function edit(Request $request, Instrument $instrument)
    {
        $request->validate([
            'name'=>'string',
            'tool_id'=>'integer|exists:tools,id',
            'manufacture_id'=>'integer|exists:manufactures,id',
            'from'=>'nullable|date',
            'to'=>'nullable|date',
            'definition'=>'nullable|string',
            'files'=>'nullable'
        ]);
        DB::beginTransaction();
        try{
            $instrument->update($request->only('name', 'type','tool_id','manufacture_id','from','to','definition'));
            if($request->file('files')){
                $file = $this->service->uploads($request->file('files'));
                $instrument->file_id = $file->id;
                $instrument->save();
            }
            DB::commit();
            return $instrument;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }
    public function remove($id)
    {
        try{
            $instrument =Instrument::findOrFail($id);
            // $this->service->delete($instrument->file_id);
            $instrument->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }

    }


}
