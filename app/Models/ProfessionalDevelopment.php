<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfessionalDevelopment extends Model
{
    protected $fillable = ['file_id', 'date', 'definition'];

    public function file(){
        return $this->belongsTo(Files::class);
    }
}
    