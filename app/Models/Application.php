<?php

namespace App\Models;

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
        'definition',
        'certificates',
        'licenses',
        'device_id',
        'user_id',
        'error_or_broken',
        'telecommunication_network',
        'provide_cyber_security',
        'threats_to_information_security',
        'consequences_of_an_incident',
        'organizational_and_technical_measures_to_ensure_security',
        'status'
    ];
    protected $casts = [
        'certificates' => 'array',
        'licenses' => 'array'
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

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

    public function getCertificateAttribute(){

        return Files::whereIn('id',$this->certificates? : [])->get();

    }

    public function getLicenseAttribute(){
        return Files::whereIn('id',$this->licenses? : [])->get();
    }

    protected static function booted(){
        static::addGlobalScope('permission', function (Builder $builder) {
            if(!Gate::any(['admin','manager'])){
                $builder->where('user_id',(int)Auth::user()->id);
            }
        });
    }

}
