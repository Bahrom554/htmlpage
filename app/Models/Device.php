<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
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
