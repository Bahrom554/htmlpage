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
        'creator_id',
        'recipient_id'
    ];


    public function application(){

        return $this->belongsTo(Application::class);
    }
    public function creator(){
        return $this->belongsTo(User::class , 'creator_id' , 'id');
    }

    public function recipient(){
        return $this->belongsTo(User::class , 'recipient_id' , 'id');
    }
}
