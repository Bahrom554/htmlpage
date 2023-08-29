<?php

namespace App\Models;

use App\Models\Files;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = ['name', 'subject_type_id', 'address_legal','address_fact', 'email','phone'];
    // protected $with = ['subjects'];

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

   public function staffs(){
       return $this->hasMany(Staff::class);
   }

}
