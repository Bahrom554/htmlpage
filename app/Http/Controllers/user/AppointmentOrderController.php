<?php

namespace App\Http\Controllers\user;

use App\AppointmentOrder;
use Illuminate\Http\Request;

class AppointmentOrderController extends Controller
{

    private $service;
    public function __construct(SubjectService $service)
    {
        $this->middleware('role:admin|manager')->only('store', 'update', 'destroy');

        $this->service=$service;
    }

    public function index()
    {
        
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(AppointmentOrder $appointmentOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AppointmentOrder  $appointmentOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(AppointmentOrder $appointmentOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AppointmentOrder  $appointmentOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppointmentOrder $appointmentOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AppointmentOrder  $appointmentOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppointmentOrder $appointmentOrder)
    {
        //
    }
}
