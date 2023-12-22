<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    protected $fillable = [
        'description',
        'column_id',
        'reject_id',
        'author'
        
    ];


    public function reject(){

        return $this->belongsTo(Reject::class);
    }
}
