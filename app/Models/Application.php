<?php

namespace App\Models;

use App\Subject;
use App\Models\Staff;
use App\Models\Comment;
use App\Models\Importance;
use App\Models\Telecommunication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Application extends Model
{
    //User actions
    public const STATUS_WAITING = 0;
    public const STATUS_REJECT =1;
    //Manager actions
    public const STATUS_MANAGER_TO_ADMIN = 2;
    public const STATUS_MANAGER_TO_USER = 3;
    //Admin actions
    public const STATUS_ADMIN_TO_MANAGER = 4;
    public const STATUS_SUCCESS = 5;


    protected $fillable = [
        'name',
        'user_id',
        'staffs',
        'scope_and_purpose',
        'importance_id',
        'document_id',
        'techniques',
        'devices',
        'licenses',
        'certificates',
        'telecommunications',
        'error_or_broken',
        'provide_cyber_security',
        'threats_to_information_security',
        'consequences_of_an_incident',
        'organizational_and_technical_measures_to_ensure_security',
        'status',
        'subject',
        'subject_type',
        'subject_definition',
        'subject_document',
    ];
    protected $casts = [
        'staffs' => 'array',
        'telecommunications' => 'array',
        'devices' => 'array',
        'techniques' => 'array',
        'licenses' => 'array',
        'certificates' => 'array'
    ];

    protected $dates = ['deleted_at'];

    protected $appends = ['staff', 'telecommunication', 'device', 'technique', 'license', 'certificate'];

    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function importance()
    {
        return $this->belongsTo(Importance::class);
    }

    public function subjectDocument()
    {

        return $this->belongsTo(Files::class, 'subject_document', 'id');
    }

    public function Document()
    {

        return $this->belongsTo(Files::class, 'document_id', 'id');
    }

    public function comment(){
        return $this->hasMany(Comment::class);
    }

    public function getCertificateAttribute()
    {

        return Files::whereIn('id', $this->certificates ?: [])->get();
    }

    public function getLicenseAttribute()
    {

        return Files::whereIn('id', $this->licenses ?: [])->get();
    }


    public function getStaffAttribute()
    {

        return Staff::whereIn('id', $this->staffs ?: [])->get();
    }

    public function getTeleCommunicationAttribute()
    {

        return Telecommunication::whereIn('id', $this->telecommunications ?: [])->get();
    }
    public function getDeviceAttribute()
    {
        return Device::whereIn('id', $this->devices ?: [])->get();
    }

    public function getTechniqueAttribute()
    {
        return Technique::whereIn('id', $this->techniques ?: [])->get();
    }

    public function scopePopular($query, $request)
    {
        if ($request->filled('between')) {
            return $query->whereBetween('updated_at', explode(',', $request->between));
        }
    }

    public function userActions()
    {
        if ($this->status == static::STATUS_MANAGER_TO_USER || $this->status == static::STATUS_WAITING) {
            return true;
        }
        return false;
    }

    public function managerActions(){
        if ($this->status == static::STATUS_ADMIN_TO_MANAGER || $this->status == static::STATUS_WAITING) {
            return true;
        }
        return false;
    }

    public function adminActions(){

        if ($this->status == static::STATUS_MANAGER_TO_ADMIN) {
            return true;
        }
        return false;
    }

    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            if (!Gate::any(['admin', 'manager'])) {
                $builder->where('user_id', (int)Auth::user()->id);
            }
            else if(!Gate::allows('manager')){
                $builder->whereIn('status', [self::STATUS_ADMIN_TO_MANAGER,self::STATUS_WAITING]);
            }

            else if(!Gate::allows('admin')){
                $builder->where('status', [self::STATUS_MANAGER_TO_ADMIN, self::STATUS_SUCCESS]);
            }
        });
    }
}
