<?php

namespace App\UseCases;
use App\Models\Files;
use App\Models\InternetProvider;
use App\Models\Provider;
use DomainException;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Models\Network;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

use Exception;

use Spatie\QueryBuilder\QueryBuilder;

class NetworkService
{

    private $fileService;
    private $internetProviderService;

    public function __construct( FileService $fileService, InternetProviderService $internetProviderService)
    {
        $this->fileService=$fileService;
        $this->internetProviderService= $internetProviderService;

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
            $file = $this->fileService->uploads($request->file('files'));
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
                $file = $this->fileService->uploads($request->file('files'));
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
    public function remove(Network  $network)
    {
        try{

            foreach ($network->internet_providers as $id) {
                if($item = InternetProvider::find($id)){
                    $this->internetProviderService->remove($item);
                }


            }
            if($file = Files::find($network->file_id)){
                $this->fileService->delete($file);
            }
            $network->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }

    }

     public function search(Request $request){
         $query = QueryBuilder::for(Network::class);
         $checker =0;
         if(!empty($request->get('network_name'))) {
             $query->where('name', $request->get('network_name'));
             $checker=1;
         }
         if($request->get('connection'))
         {
             if(!empty($request->get('internet_provider_name'))){
                 $q = QueryBuilder::for(InternetProvider::class);
                  $q->whereHas('provider', function (Builder $b) use ($request){
                      $b->where('name',$request->get('internet_provider_name'));
                  });
                 $internet_providers = $q->get()->pluck('id')->toArray();
                 if(empty($internet_providers)) $query->where('id',0);
                 $query->where(function ($q) use($internet_providers){
                     foreach ($internet_providers as $provider){
                         $q->orWhereJsonContains('internet_providers',"{$provider}");
                     }
                 });

                 $checker=1;
             }
         }
         $ids = $query->get()->pluck('id')->toArray();
         if($checker) return $ids;
          return null;
     }

}
