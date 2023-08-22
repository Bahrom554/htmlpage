<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compliance extends Model
{
    

    protected $fillable = ['file_id', 'from','to', 'definition'];

    public function file(){
        return $this->belongsTo(Files::class);
    }
}
    