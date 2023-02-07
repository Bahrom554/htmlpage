<?php

namespace App\Models;

use App\Subject;
use App\Models\Importance;
use App\Models\Staff;
use App\Models\Telecommunication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


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
        'license_id',
        'certificate_id',
        'telecommunications',
        'provide_cyber_security',
        'threats_to_information_security',
        'consequences_of_an_incident',
        'organizational_and_technical_measures_to_ensure_security',
        'status',
        'reason',
        'subject',
        'subject_type',
        'subject_definition',
        'subject_document'
    ];
    protected $casts = [
        'staffs' => 'array',
        'telecommunications'=>'array',
        'devices'=>'array',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['staff','telecommunication','device'];
   
    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function importance(){
        return $this->belongsTo(Importance::class);
    }
   
    public function certificate(){

        return $this->belongsTo(Files::class,'certificate_id','id');

    }

    public function license(){

        return $this->belongsTo(Files::class,'license_id','id');

    }
    public function subjectDocument(){

        return $this->belongsTo(Files::class,'subject_document','id');

    }

    public function getStaffAttribute(){

        return Staff::whereIn('id',$this->staffs? : [])->get();

    }

    public function getTeleCommunicationAttribute(){

        return Telecommunication::whereIn('id',$this->telecommunications? : [])->get();

    }
    public function getDeviceAttribute(){

        return Device::whereIn('id',$this->devices? : [])->get();

    }

    public function scopePopular($query, $request)
    {
        if ($request->filled('between')) {
            return $query->whereBetween('updated_at', explode(',',$request->between));
        }
    }

    protected static function booted(){
        static::addGlobalScope('permission', function (Builder $builder) {
            if(!Gate::any(['admin','manager'])){
                $builder->where('user_id',(int)Auth::user()->id);
            }
        });
    }
   

}
