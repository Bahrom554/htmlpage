<?php

namespace App\UseCases;
use Illuminate\Http\Request;
use App\UseCases\FileService;
use App\Models\AppointmentOrder;
use Illuminate\Support\Facades\DB;

use Exception;

class AppointmentOrderService
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

            $appointment_order = AppointmentOrder::make($request->only('definition', 'date'));
            $file = $this->service->uploads($request->file('files'));
            $appointment_order->file_id = $file->id;
            $appointment_order->save();
            DB::commit();
            return $appointment_order;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }

    public function edit(Request $request, AppointmentOrder $appointment_order)
    {
        $request->validate([
            'definition'=>'nullable|string',
            'date'=>'date|before:now',
            'files'=>'nullable'
        ]);
        DB::beginTransaction();
        try{
            $appointment_order->definition =$request->definition;
            $appointment_order->date =$request->date;
            if($request->file('files')){
                $file = $this->service->uploads($request->file('files'));
                $appointment_order->file_id = $file->id;
            }
            $appointment_order->save();
            DB::commit();
            return $appointment_order;

        }catch (\Exception $e) {
            DB::rollBack();
            throw new DomainException($e->getMessage(), $e->getCode());
        }



    }
    public function remove($id)
    {
        try{
            $appointment_order =AppointmentOrder::findOrFail($id);
            // $this->service->delete($appointment_order->file_id);
            $appointment_order->delete();
            return 'deleted';
        }catch(Exception $e)
        {
            return $e;
        }

    }


}
