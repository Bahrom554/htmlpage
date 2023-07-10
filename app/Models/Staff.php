<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;



class Staff extends Model
{
    protected $fillable=[
        'name',
        'phone',
        'statue',
        'definition',
        'user_id'
    ];


    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            if (Gate::allows('user')) {
                $builder->where('user_id', (int)Auth::user()->id);
            }
    });
    }
}

