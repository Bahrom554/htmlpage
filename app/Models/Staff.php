<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;



class Staff extends Model
{
    protected $fillable=[
        'subject_id',
        'appointment_order_id',
        'diploma_id',
        'professional_development',
        'compliance_id',
        'name',
        'position',
        'phone',
        'definition'
    ];

    protected $casts = [
        'professional_development'=>'array'
    ];

    protected $with =['diploma','appointment','compliance'];
    protected $appends = ['professional'];
    public function subject(){
        return $this->belongsTo(Subject::class);
    }

    public function diploma(){
        return $this->belongsTo(Diploma::class);
    }

    public function appointment(){
        return $this->belongsTo(AppointmentOrder::class,'appointment_order_id','id');
    }

    public function getProfessionalAttribute(){
        return ProfessionalDevelopment::whereIn('id', $this->professional_development ?: [])->get();
    }

    public function compliance(){
        return $this->belongsTo(Compliance::class);
    }



    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            if (Gate::allows('user')) {
                $builder->where('subject_id', (int)Auth::user()->subject_id);
            }
    });
    }
}

