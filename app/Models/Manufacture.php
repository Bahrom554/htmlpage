<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    protected $fillable =['name','definition'];

    public function tools(){
        return $this->belongsToMany(Tool::class,'manufacture_tool');
    }
}
