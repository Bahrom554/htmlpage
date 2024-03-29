<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purpose extends Model
{
    protected $fillable = ['name'];

    public function applications():HasMany
    {
        return $this->hasMany(Application::class);
    }
}
