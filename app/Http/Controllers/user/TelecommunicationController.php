<?php

namespace App\Http\Controllers\user;


use Illuminate\Http\Request;
use App\Models\Telecommunication;
use App\Http\Controllers\Controller;

class TelecommunicationController extends Controller
{
    public function store(Request $request)
    {
        $validated=$request->validate([
            'provider'=>'required|string',
            'contract'=>'required|string',
            'documents'=>'required|array|exists:files,id',
            

        ]);
        $telec=Telecommunication::create($validated); 
        return $telec;
    }

   
    public function show(Telecommunication $telecommunication)
    {
        return $telecommunication;
    }

   

    
    public function update(Request $request, Telecommunication $telecommunication)
    {
        $validated=$request->validate([
            'provider'=>'required|string',
            'contract'=>'required|string',
            'documents'=>'required|array|exists:files,id'
         ]);
         $telecommunication->update($request->only('provider','contract','documents'));
         return $telecommunication;
    }

   
    public function destroy(Telecommunication $telecommunication)
    {
        $telecommunication->delete();
        return 'deleted';
    }
}
