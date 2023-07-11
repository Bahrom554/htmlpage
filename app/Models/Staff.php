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
        'file_1',
        'file_2',
        'file_3',
        'subject_id'
    ];

    
   

    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            if (Gate::allows('user')) {
                $builder->where('subject_id', (int)Auth::user()->subject_id);
            }
    });
    }
}

