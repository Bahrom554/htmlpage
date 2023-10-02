<?php

namespace App\Models;

use App\Models\Files;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;

class InternetProvider extends Model
{
    protected $fillable =['provider_id','file_id','points'];
    
    protected $with =['file','provider'];
    public function file(){
        return $this->belongsTo(Files::class);
    }

    public function provider(){

        return $this->belongsTo(Provider::class);
    }
}
