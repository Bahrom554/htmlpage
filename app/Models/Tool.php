<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    public const TYPE_INFORMATION =1;
    public const TYPE_CYBER_SECURITY =2;

    protected $fillable =['name','type'];

    public function manufactures(){
        return $this->belongsToMany(Manufacture::class,'manufacture_tool');
    }

    public function instruments(){
        return $this->hasMany(Instrument::class);
    }
}
