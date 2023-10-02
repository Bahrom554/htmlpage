<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;

class Telecommunication extends Model
{
    protected $fillable=['name','network_topology','contract','connect_net','connect_nat','points_connect_net','provider_count'];
   
    public function contract_file(){
      return $this->belongsTo(Files::class,'contract','id');
    }

    public function network_topology_file(){
        return $this->belongsTo(Files::class,'network_topology','id');
    }
}
