<?php

namespace App\Models;

use App\Staff;
use App\Telecomunication;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class Application extends Model
{
    public const STATUS_REJECT = 0;
    public const STATUS_WAITING = 1;
    public const STATUS_PROCESS = 2;
    public const STATUS_SUCCESS = 3;

    protected $fillable = [
        'name',
        'user_id',
        'staffs',
        'scope_and_purpose',
        'importance_id',
        'error_or_broken',
        'devices',
        'documents',
        'telecommunication',
        'provide_cyber_security',
        'threats_to_information_security',
        'consequences_of_an_incident',
        'organizational_and_technical_measures_to_ensure_security',
        'status',
        'reason'
    ];
    protected $casts = [
        'staffs' => 'array',
        'documents' => 'array',
        'telecommunications'=>'array',
        'devices'=>'array'
    ];

    protected $dates = ['deleted_at'];

    

    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function scopePopular($query, $request)
    {
        if ($request->filled('between')) {
            return $query->whereBetween('updated_at', explode(',',$request->between));
        }
    }

    public function getDocumentAttribute(){

        return Files::whereIn('id',$this->documents? : [])->get();

    }

    public function getStaffAttribute(){

        return Staff::whereIn('id',$this->staffs? : [])->get();

    }

    public function getTeleComunicationAttribute(){

        return Telecomunication::whereIn('id',$this->telecomunications? : [])->get();

    }
    public function getDeviceAttribute(){

        return Device::whereIn('id',$this->devices? : [])->get();

    }

    protected static function booted(){
        static::addGlobalScope('permission', function (Builder $builder) {
            if(!Gate::any(['admin','manager'])){
                $builder->where('user_id',(int)Auth::user()->id);
            }
        });
    }

}
