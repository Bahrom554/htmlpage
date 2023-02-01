<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;

class Telecomunication extends Model
{
    protected $fillable=['provider','contract','documents'];
    protected $casts=[
        'documents'=>'array'
    ];
    public function getDocumentAttribute(){
        return Files::whereIn('id',$this->certificates? : [])->get();
       }
}
