<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manufacture extends Model
{
    protected $fillable =['name','definition'];

    public function tool_types(){
        return $this->belongsToMany(ToolType::class,'manufacture_tool_type');
    }

    public function tools(){
        return $this->hasMany(Tool::class);
    }
}
