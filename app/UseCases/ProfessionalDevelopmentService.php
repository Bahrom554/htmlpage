<?php

namespace App\UseCases;
use Exception;
use Illuminate\Http\Request;
use App\Models\ProfessionalDevelopment;
use Illuminate\Support\Facades\DB;


class ProfessionalDevelopmentService
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
           'date'=>'required|date|before:now',
           'files'=>'required'
        ]);
        DB::beginTransaction();
        try{
            $professional_development = ProfessionalDevelopment::make($request->only('definition', 'date'));
                $file = $this->service->uploads($request->file('files'));
                $professional_development->file_id = $file->id;
            $professional_development->save();
            DB::commit();
            return $professional_development;
        }catch(\Exception $e){
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }

    }

    public function edit(Request $request, ProfessionalDevelopment $professional_development)
    {
        $request->validate([
            'definition'=>'nullable|string',
            'date'=>'date|before:now',
            'files'=>'nullable'
        ]);
        DB::beginTransaction();
        try{
            $professional_development->definition = $request->definition;
            $professional_development->date = $request->date;
            if($request->file('files')){
                $file = $this->service->uploads($request->file('files'));
                $professional_development->file_id = $file->id;
            }
            DB::commit();
            return $professional_development;
        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }
    public function remove($id)
    {
        try{
            $professional_development =ProfessionalDevelopment::findOrFail($id);
            // $this->service->delete($professional_development->file_id);
            $professional_development->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }
    }


}
