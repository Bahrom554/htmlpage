<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name', 'type', 'definition', 'documents'];
    protected $casts=[
        'documents'=>'array'
    ];


     protected $appends = ['document'];

   public function getDocumentAttribute(){
    return Files::whereIn('id',$this->documents? : [])->get();
   }


   public function applications():HasMany
   {
       return $this->hasMany(Application::class);
   }

}
