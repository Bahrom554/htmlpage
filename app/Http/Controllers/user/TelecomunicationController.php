<?php

namespace App\Http\Controllers\user;

use Illuminate\Http\Request;
use App\Models\Telecomunication;
use NunoMaduro\Collision\Provider;
use App\Http\Controllers\Controller;

class TelecomunicationController extends Controller
{
    
    public function store(Request $request)
    {
        $validated=$request->validate([
            'provider'=>'required|string',
            'contract'=>'required|string',
            'documents'=>'required|array|exists:files,id',
            

        ]);
        $telec=Telecomunication::create($validated); 
        return $telec;
    }

   
    public function show(Telecomunication $telecomunication)
    {
        return $telecomunication;
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Telecomunication  $telecomunication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Telecomunication $telecomunication)
    {
        $validated=$request->validate([
            'provider'=>'required|string',
            'contract'=>'required|string',
            'documents'=>'required|array|exists:files,id'
         ]);
         $telecomunication->update($request->only('provider','contract','documents'));
         return $telecomunication;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Telecomunication  $telecomunication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Telecomunication $telecomunication)
    {
        $telecomunication->delete();
        return 'deleted';
    }
}
