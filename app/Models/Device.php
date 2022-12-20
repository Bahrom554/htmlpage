<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    protected $fillable = ['ram', 'hdd', 'ssd', 'cpu', 'architecture', 'power', 'os', 'version', 'case', 'type', 'slot', 'definition'];
    protected $casts = [
        'ram' => 'array',
        'hdd' => 'array',
        'ssd' => 'array',
        'cpu' => 'array',
        'architecture' => 'array',
        'power' => 'array',
        'os' => 'array',
        'version' => 'array',
        'case' => 'array',
        'type' => 'array',
        'slot' => 'array',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
