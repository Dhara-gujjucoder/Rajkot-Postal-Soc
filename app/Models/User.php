<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use HasFactory, SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_member',
        'notification_email'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class);
    // }
    public function scopeAdmin($query)
    {
        return $query->when(!auth()->user()->is_member, function ($q) {
            $q->where('is_member', 0);
        });
    }

    public function scopeUserMember($query)
    {
        return $query->where('is_member', 1)->whereExists(function (Builder $q) {
            $q->select(DB::raw(1))
                  ->from('members')
                  ->whereColumn('members.user_id', 'users.id');
        });
    }

    public function member()
    {
        return $this->hasOne(Member::class)->withTrashed();
    }

    public function getFullnameAttribute()
    {
        return $this->name. ' ( '.$this->member->registration_no.' )';
    }
}
