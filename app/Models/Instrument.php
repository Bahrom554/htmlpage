<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    public const TYPE_INFORMATION =1;
    public const TYPE_CYBER_SECURITY =2;
    protected $fillable = ['type','name','tool_id','manufacture_id','from','to','definition','file_id'];

    public function tool(){
        return $this->belongsTo(Tool::class);

    }

    public function manufacture(){
        return $this->belongsTo(Manufacture::class);
    }

    public function file(){
        return $this->belongsTo(Files::class);
    }

}
