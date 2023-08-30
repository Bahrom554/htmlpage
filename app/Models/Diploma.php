<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diploma extends Model
{
    protected $fillable = ['file_id', 'educational_institution', 'degree','definition'];

    public function file(){
        return $this->belongsTo(Files::class);
    }

    public function staff(){
        return $this->hasOne(Staff::class);
    }
}
