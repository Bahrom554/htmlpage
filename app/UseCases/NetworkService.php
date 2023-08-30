<?php

namespace App\UseCases;
use App\Models\InternetProvider;
use App\Models\Provider;
use DomainException;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Models\Network;
use Illuminate\Support\Facades\DB;

use Exception;
use Spatie\QueryBuilder\QueryBuilder;

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

     public function search(Request $request){
         $query = QueryBuilder::for(Network::class);

         if(!empty($request->get('network_name'))) $query->where('name', 'like', '%' . $request->get('network_name') . '%');
         if(!empty($request->get('connection'))) $query->where('connection',$request->get('connection'));
         if(!empty($request->get('internet_provider_name'))){
             $providers =Provider::where('name', 'like', '%' . $request->get('internet_provider_name') . '%')->pluck('id')->toArray();
             $q = QueryBuilder::for(InternetProvider::class);
             $q->whereIn('provider_id', $providers? :[]);
             if(!empty($request->get('points'))) $q->where('points',$request->get('points'));
             $internet_providers = $q->pluck('id')->toArray();
             $query->whereJsonContains('internet_providers',$internet_providers);
         }

         $ids = $query->pluck('id')->toArray();

         return $ids;
     }

}
