<?php

namespace App\UseCases;
use DomainException;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Models\Network;
use Illuminate\Support\Facades\DB;

use Exception;

class NetworkService
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
           'internet_providers'=>'required|array|exists:internet_providers,id',
           'connection'=>'required|boolean',
           'files'=>'required'
        ]);
        DB::beginTransaction();

        try{

            $network = Network::make($request->only('name', 'internet_providers','connection'));
            $file = $this->service->uploads($request->file('files'));
            $network->file_id = $file->id;
            $network->save();
            DB::commit();
            return $network;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }

    public function edit(Request $request, Network $network)
    {
        $request->validate([
            'name'=>'string',
           'internet_providers'=>'array|exists:internet_providers,id',
           'connection'=>'boolean',
           'files'=>'nullable'
        ]);
        DB::beginTransaction();
        try{
           if($request->name) $network->name =$request->name;
           if($request->internet_provider) $network->internet_providers =$request->internet_providers;
           if($request->connection) $network->connection = $request->connection;
            if($request->file('files')){
                $file = $this->service->uploads($request->file('files'));
                $network->file_id = $file->id;
            }
            $network->save();
            DB::commit();
            return $network;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }
    public function remove($id)
    {
        try{
            $network =Network::findOrFail($id);
            // $this->service->delete($network->file_id);
            $network->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }

    }


}
