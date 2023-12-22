<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compliance extends Model
{


    protected $fillable = ['file_id', 'from','to', 'definition'];

    protected $with =['file'];
    public function file(){
        return $this->belongsTo(Files::class);
    }

    public function staff(){
        return $this->hasOne(Staff::class);
    }

}
