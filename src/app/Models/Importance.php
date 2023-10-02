<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Importance extends Model
{
    protected $fillable =[
        'name',
        'definition'
    ];

    public function applications():HasMany
    {
        return $this->hasMany(Application::class);
    }
}
