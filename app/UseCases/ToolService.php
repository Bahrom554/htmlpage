<?php

namespace App\UseCases;
use App\Models\Files;
use App\Models\Manufacture;
use App\Models\Network;
use Carbon\Carbon;
use DomainException;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Models\Tool;
use App\Models\ToolType;
use Illuminate\Support\Facades\DB;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\QueryBuilder;

class ToolService
{

    private $fileService;

    public function __construct( FileService $fileService)
    {
        $this->fileService=$fileService;

    }
    public function create(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'category'=>'required|integer|in:1,2',
            'tool_type_id'=>'required|integer|exists:tool_types,id',
            'manufacture_id'=>'required|integer|exists:manufactures,id',
            'from'=>'nullable|date',
            'to'=>'nullable|date',
            'definition'=>'nullable|string',
            'files'=>'required'
        ]);
        DB::beginTransaction();

        try{

            $tool = Tool::make($request->only('name','tool_type_id','manufacture_id','definition','category'));
            $file = $this->fileService->uploads($request->file('files'));
            $tool->file_id = $file->id;
            $tool->from = Carbon::createFromFormat('Y-m-d',$request->from)->startOfDay();
            $tool->to = Carbon::createFromFormat('Y-m-d',$request->to)->endOfDay();
            $tool->save();
            DB::commit();
            return $tool;

        }catch (\Exception $e) {
            DB::rollBack();
             return $e;
//             throw new DomainException($e->getMessage(), $e->getCode());
        }



    }

    public function edit(Request $request, Tool $tool )
    {
        $request->validate([
            'name'=>'string',
            'tool_type_id'=>'integer|exists:tools,id',
            'manufacture_id'=>'integer|exists:manufactures,id',
            'from'=>'nullable|date',
            'to'=>'nullable|date',
            'definition'=>'nullable|string',
            'files'=>'nullable'
        ]);
        DB::beginTransaction();
        try{
            $tool ->update($request->only('name','tool_type_id','manufacture_id','definition'));
            if($request->file('files')){
                $file = $this->fileService->uploads($request->file('files'));
                $tool ->file_id = $file->id;
                $tool->from = Carbon::createFromFormat('Y-m-d',$request->from)->startOfDay();
                $tool->to = Carbon::createFromFormat('Y-m-d',$request->to)->endOfDay();
                $tool ->save();
            }
            DB::commit();
            return $tool ;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }
    public function remove(Tool $tool)
    {
        try{
            if($file = Files::find($tool->file_id)){
                $this->fileService->delete($file);
            }
            $tool ->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }

    }

    public function searchInformationTool(Request $request){

        $query = QueryBuilder::for(Tool::class);
        $checker=0;
        $query->where('category',Tool::CATEGORY_INFORMATION);
        if(!empty($request->get('information_tool_name'))) {
            $query->where('name',  $request->get('information_tool_name'));
            $checker=1;
        }
        if(!empty($request->get('information_tool_type'))){
            $query->whereHas('type', function (Builder $q) use ($request){
                $q->where('name', $request->get('information_tool_type'));
            });
            $checker=1;
        }

        if(!empty($request->get('information_tool_manufacture'))){
           $query->whereHas('manufacture', function (Builder $q) use ($request){
             $q->where('name',$request->get('information_tool_manufacture'));
           });

            $checker=1;
        }

       if(!empty($request->get('information_tool_from')) && !empty($request->get('information_tool_to')) ){
           $from = Carbon::createFromFormat('Y-m-d',$request->get('information_tool_from'))->startOfDay();
           $to =Carbon::createFromFormat('Y-m-d',$request->get('information_tool_to'))->endOfDay();
           $query->whereBetween('to',[$from,$to]);
           $checker=1;
       }

         $ids= $query->get()->pluck('id')->toArray();
        if($checker) return $ids;
        return null;
    }

    public function searchCybersecurityTool(Request $request){

        $query = QueryBuilder::for(Tool::class);
        $checker=0;
        $query->where('category',Tool::CATEGORY_CYBERSECURITY);
        if(!empty($request->get('cyber_tool_name'))) {
            $query->where('name',  $request->get('cyber_tool_name'));
            $checker=1;
        }
        if(!empty($request->get('cyber_tool_type'))){
            $query->whereHas('type', function (Builder $q) use ($request){
                $q->where('name', $request->get('cyber_tool_type'));
            });
            $checker=1;
        }

        if(!empty($request->get('cyber_tool_manufacture'))){
            $query->whereHas('manufacture', function (Builder $q) use ($request){
                $q->where('name',$request->get('cyber_tool_manufacture'));
            });

            $checker=1;
        }

        if(!empty($request->get('cyber_tool_from')) && !empty($request->get('cyber_tool_to')) ){
            $from = Carbon::createFromFormat('Y-m-d',$request->get('cyber_tool_from'))->startOfDay() ;
            $to = Carbon::createFromFormat('Y-m-d',$request->get('cyber_tool_to'))->endOfDay();
            $query->whereBetween('to',[$from,$to]);
            $checker=1;
        }

        $ids= $query->get()->pluck('id')->toArray();

        if($checker) return $ids;
        return null;
    }


}
