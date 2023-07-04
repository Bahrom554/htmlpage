<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use App\Models\Application;
use Illuminate\Database\Eloquent\Model;

class Reject extends Model
{
    protected $fillable = [
        'application_id',
        'author',
    ];


// protected $with = ['comment'];

    public function application(){

        return $this->belongsTo(Application::class);
    }
    public function author(){
        return $this->belongsTo(User::class , 'author' , 'id');
    }

    public function comment(){
        return $this->hasMany(Comment::class);
    }
    
}
