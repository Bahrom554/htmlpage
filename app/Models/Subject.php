<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name', 'parent_id', 'address', 'documents'];
    // protected $with = ['subjects'];
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

   public function user(){
    return $this->hasMany(User::class);
   }

   public function subjects(){
    return $this->hasMany(Subject::class,'parent_id','id');
   }

//    protected static function booted()
//     {
//         static::addGlobalScope('permission', function (Builder $builder) {
            
//                 $builder->whereNull('parent_id');
            
           
//         });
//     }

}
