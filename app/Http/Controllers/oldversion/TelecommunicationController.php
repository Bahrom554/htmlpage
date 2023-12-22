<?php

namespace App\Http\Controllers\oldversion;


use App\Http\Controllers\Controller;
use App\Models\Telecommunication;
use Illuminate\Http\Request;

class TelecommunicationController extends Controller
{
    public function store(Request $request)
    {
        $validated=$request->validate([
            'name'=>'required|string',
            'network_topology'=>'nullable|integer|exists:files,id',
            'contract'=>'nullable|integer|exists:files,id',
            'connect_net'=>'nullable|boolean',
            'connect_nat'=>'nullable|boolean',
            'points_connect_net'=>'nullable|integer',
            'provider_count'=>'nullable|integer'

        ]);
        $telec=Telecommunication::create($validated);
        return $telec;
    }


    public function show(Request $request,Telecommunication $telecommunication)
    {

        if (!empty($request->include)) {
            $telecommunication->load(explode(',', $request->include));
        }

        return $telecommunication;
    }




    public function update(Request $request, Telecommunication $telecommunication)
    {
        $validated=$request->validate([
            'name'=>'required|string',
            'network_topology'=>'nullable|integer|exists:files,id',
            'contract'=>'nullable|integer|exists:files,id',
            'connect_net'=>'nullable|boolean',
            'connect_nat'=>'nullable|boolean',
            'points_connect_net'=>'nullable|integer',
            'provider_count'=>'nullable|integer'
         ]);
         $telecommunication->update($validated);
         return $telecommunication;
    }


    public function destroy(Telecommunication $telecommunication)
    {
        $telecommunication->delete();
        return 'deleted';
    }
}
