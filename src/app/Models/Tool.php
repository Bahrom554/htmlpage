<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    public const CATEGORY_INFORMATION =1;
    public const CATEGORY_CYBERSECURITY =2;
    protected $fillable = ['category','name','tool_type_id','manufacture_id','from','to','definition','file_id'];

    protected $with = ['file','manufacture','type'];
    public function type(){
        return $this->belongsTo(ToolType::class,'tool_type_id','id');

    }

    public function manufacture(){
        return $this->belongsTo(Manufacture::class);
    }

    public function file(){
        return $this->belongsTo(Files::class);
    }

}
