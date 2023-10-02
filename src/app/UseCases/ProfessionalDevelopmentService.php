<?php

namespace App\UseCases;
use App\Models\Files;
use Exception;
use DomainException;
use Illuminate\Http\Request;
use App\Models\ProfessionalDevelopment;
use Illuminate\Support\Facades\DB;


class ProfessionalDevelopmentService
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
           'date'=>'required|date|before:now',
           'files'=>'required'
        ]);
        DB::beginTransaction();
        try{
            $professional_development = ProfessionalDevelopment::make($request->only('definition', 'date'));
                $file = $this->fileService->uploads($request->file('files'));
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
                $file = $this->fileService->uploads($request->file('files'));
                $professional_development->file_id = $file->id;
            }
            DB::commit();
            return $professional_development;
        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }
    public function remove(ProfessionalDevelopment $development)
    {
        try{

            if($file = Files::find($development->file_id)){
                $this->fileService->delete($file);
            }

            $development->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }
    }


}
