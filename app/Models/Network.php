<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    protected $fillable =['name','file_id','connection','internet_providers'];

    protected $casts = ['internet_providers'=>'array'];

    protected $with =['file'];
    protected $appends = ['internets'];
    public function getInternetsAttribute(){
        return InternetProvider::whereIn('id', $this->internet_providers)->get();
    }

    public function file(){
        return $this->belongsTo(Files::class);
    }
}
