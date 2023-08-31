<?php

namespace App\UseCases;
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
use Spatie\QueryBuilder\QueryBuilder;

class ToolService
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
            $file = $this->service->uploads($request->file('files'));
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
                $file = $this->service->uploads($request->file('files'));
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
    public function remove($id)
    {
        try{
            $tool  =Tool::findOrFail($id);
            // $this->service->delete($tool ->file_id);
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
            $tool_types =ToolType::where('name',  $request->get('information_tool_type'))->where('category',ToolType::CATEGORY_INFORMATION)->pluck('id')->toArray();
            $query->whereIn('tool_type_id',$tool_types);
            $checker=1;
        }
        if(!empty($request->get('information_tool_manufacture'))){
            $manufactures =Manufacture::where('name', $request->get('information_tool_manufacture') )->pluck('id')->toArray();
            $query->whereIn('manufacture_id',$manufactures);
            $checker=1;
        }

       if(!empty($request->get('information_tool_from'))){
           $from = Carbon::createFromFormat('Y-m-d',$request->get('information_tool_from'))->startOfDay();
           $query->whereDate('from',$from);
           $checker=1;
       }
        if(!empty($request->get('information_tool_to'))){
            $to = Carbon::createFromFormat('Y-m-d',$request->get('information_tool_to'))->startOfDay();
            $query->whereDate('to',$to);
            $checker=1;
        }

       $ids= $query->pluck('id')->toArray();
        if($checker && !empty($ids)) return $ids;
        return null;
    }

    public function searchCybersecurityTool(Request $request){

        $query = QueryBuilder::for(Tool::class);
        $checker=0;
        $query->where('category', Tool::CATEGORY_CYBERSECURITY);
        if(!empty($request->get('cyber_tool_name'))) {
            $query->where('name',  $request->get('cyber_tool_name') );
            $checker=1;
        }
        if(!empty($request->get('cyber_tool_type'))){
            $tool_types =ToolType::where('name',  $request->get('cyber_tool_type') )->where('category',ToolType::CATEGORY_CYBERSECURITY)->pluck('id')->toArray();
            $query->whereIn('tool_type_id',$tool_types);
            $checker=1;
        }
        if(!empty($request->get('cyber_tool_manufacture'))){
            $manufactures =Manufacture::where('name',$request->get('cyber_tool_manufacture') )->pluck('id')->toArray();
            $query->whereIn('manufacture_id',$manufactures);
            $checker=1;
        }

        if(!empty($request->get('cyber_tool_from'))){
            $from = Carbon::createFromFormat('Y-m-d',$request->get('cyber_tool_from'))->startOfDay();
            $query->whereDate('from',$from);
            $checker=1;
        }
        if(!empty($request->get('cyber_tool_to'))){
            $to = Carbon::createFromFormat('Y-m-d',$request->get('cyber_tool_to'))->startOfDay();
            $query->whereDate('to',$to);
            $checker=1;
        }

       $ids= $query->pluck('id')->toArray();
        if($checker && !empty($ids)) return $ids;
        return null;
    }


}
