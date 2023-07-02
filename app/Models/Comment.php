<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'application_id',
        'description',
        'column_id',
        'author',
    ];


    public function application(){

        return $this->belongsTo(Application::class);
    }
    public function author(){
        return $this->belongsTo(User::class , 'author' , 'id');
    }

    
}
