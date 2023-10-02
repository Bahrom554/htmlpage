<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolType extends Model
{
    public const CATEGORY_INFORMATION =1;
    public const CATEGORY_CYBERSECURITY =2;

    protected $fillable =['name','category'];

    public function manufactures(){
        return $this->belongsToMany(Manufacture::class,'manufacture_tool_type');
    }

    public function tools(){
        return $this->hasMany(Tool::class);
    }


}
