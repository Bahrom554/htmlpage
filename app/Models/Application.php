<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\Reject;
use App\Models\Comment;
use App\Models\Subject;
use App\Models\Importance;
use App\Models\Instrument;
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
        'staff_id',
        'subject_id',
        'level_and_function',
        'importance_id',
        'information_tool',
        'cybersecurity_tool',
        'network_id',
        'provide_cyber_security',
        'threats_to_information_security',
        'consequences_of_an_incident',
        'status',
        

    ];
    protected $casts = [
        'information_tool' => 'array',
        'cybersecurity_tool' => 'array',
    ];

    protected $dates = ['deleted_at'];

    public function user()
    {
       return $this->belongsTo(User::class);
    }

    public function importance()
    {
        return $this->belongsTo(Importance::class);
    }
   
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function reject(){
        return $this->hasMany(Reject::class)->orderBy('id', 'DESC')->limit(1);
    }
    public function rejectAll(){
        return $this->hasMany(Reject::class,'application_id','id')->orderBy('id', 'DESC');
    }

    public function getInformationAttribute()
    {
        return Instrument::whereIn('id', $this->information_tool?: [])->where('type',1)->get();
    }

    public function getCybersecurityAttribute()
    {
        return Instrument::whereIn('id', $this->cybersecurity_tool?: [])->where('type',2)->get();
    }

   

    public function scopePopular($query, $request)
    {
        if ($request->filled('between')) {
            return $query->whereBetween('updated_at', explode(',', $request->between));
        }
    }

    public function userActions()
    {
        if ($this->status == static::STATUS_MANAGER_TO_USER || $this->status == static::STATUS_WAITING || $this->status == static::STATUS_REJECT) {
            return true;
        }
        return false;
    }

    public function managerActions(){
        if (Gate::allows('manager') && ($this->status == static::STATUS_ADMIN_TO_MANAGER || $this->status == static::STATUS_WAITING )) {
            return true;
        }
        return false;
    }

    public function adminActions(){

        if (Gate::allows('admin') && ($this->status == static::STATUS_MANAGER_TO_ADMIN || $this->status == static::STATUS_SUCCESS)) {
            return true;
        }
        return false;
    }

    protected static function booted()
    {
        static::addGlobalScope('permission', function (Builder $builder) {
            if (Gate::allows('user')) {
                $builder->where('user_id', (int)Auth::user()->id);
            }
            // elseif(Gate::allows('manager')){
            //     $builder->whereIn('status', [self::STATUS_ADMIN_TO_MANAGER,self::STATUS_WAITING,self::STATUS_REJECT]);
            // }

            // elseif(Gate::allows('admin')){
            //     $builder->where('status', [self::STATUS_MANAGER_TO_ADMIN, self::STATUS_SUCCESS]);
            // }
        });
    }
}
