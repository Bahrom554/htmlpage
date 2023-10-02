<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;

class Technique extends Model
{
    protected $fillable = ['name', 'manufacturer', 'type', 'license','from','to', 'definition'];

    public function manufacturer(){
        return $this->belongsTo();
    }

    public function license(){
        return $this->belongsTo(Files::class,'license','id');
    }

}
