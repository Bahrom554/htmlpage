<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentOrder extends Model
{
    protected $fillable = ['file_id', 'date', 'definition'];

    public function file(){
        return belongsTo(File::class);
    }
}
