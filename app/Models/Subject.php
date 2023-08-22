<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name', 'subject_type_id', 'address_legal','address_fact', 'documents'];
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

   public function users(){
    return $this->hasMany(User::class);
   }

   public function type(){
    return $this->belongsTo(SubjectType::class,'subject_type_id','id');
   }

  

//    protected static function booted()
//     {
//         static::addGlobalScope('permission', function (Builder $builder) {
            
//                 $builder->whereNull('parent_id');
            
           
//         });
//     }

}
