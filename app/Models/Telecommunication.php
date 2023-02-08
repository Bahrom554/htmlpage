<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;

class Telecommunication extends Model
{
    protected $fillable=['provider','contract','documents'];
    protected $casts=[
        'documents'=>'array'
    ];
    protected $appends = ['document'];
    public function getDocumentAttribute(){
        return Files::whereIn('id',$this->documents? : [])->get();
       }
}
