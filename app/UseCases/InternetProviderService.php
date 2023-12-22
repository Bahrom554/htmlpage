<?php

namespace App\UseCases;
use App\Models\Files;
use DomainException;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Models\InternetProvider;
use Illuminate\Support\Facades\DB;

use Exception;

class InternetProviderService
{

    private $fileService;

    public function __construct( FileService $fileService)
    {
        $this->fileService=$fileService;

    }
    public function create(Request $request)
    {
        $request->validate([
           'provider_id'=>'required|integer|exists:providers,id',
           'points'=>'required|integer',
           'files'=>'required'
        ]);
        DB::beginTransaction();

        try{

            $internet_provider = InternetProvider::make($request->only('points', 'provider_id'));
            $file = $this->fileService->uploads($request->file('files'));
            $internet_provider->file_id = $file->id;
            $internet_provider->save();
            DB::commit();
            return $internet_provider;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }

    public function edit(Request $request, InternetProvider $internet_provider)
    {
        $request->validate([
            'provider_id'=>'integer|exists:providers,id',
            'points'=>'integer',
            'files'=>'nullable'
        ]);
        DB::beginTransaction();
        try{
           if($request->provider_id) $internet_provider->provider_id =$request->provider_id;
           if($request->points) $internet_provider->points =$request->points;
            if($request->file('files')){
                $file = $this->fileService->uploads($request->file('files'));
                $internet_provider->file_id = $file->id;
            }
            $internet_provider->save();
            DB::commit();
            return $internet_provider;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }
    public function remove(InternetProvider $internetProvider)
    {
        try{

            if($file = Files::find($internetProvider->file_id)){
                $this->fileService->delete($file);
            }
            $internetProvider->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }

    }


}
