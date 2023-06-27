<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;

class Technique extends Model
{
    protected $fillable = ['name', 'manufacturer', 'model', 'version', 'documents'];
   
    protected $casts=[
        'documents'=>'array'
    ];


     protected $appends = ['document'];

   public function getDocumentAttribute(){
    return Files::whereIn('id',$this->documents? : [])->get();
   }

}
