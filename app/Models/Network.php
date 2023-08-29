<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable =['name','file_id','connection','internet_providers'];

    protected $casts = ['internet_providers'=>'array'];
}
