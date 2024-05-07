<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
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
    ];

    public function userAccess()
    {
        return $this->hasOne(Access::class, 'id', 'access');
    }

    
    public function userGroups()
    {
        return $this->hasMany(UserGroups::class, 'user_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Groups::class, 'user_groups');
    }
    
    public function cuttingOrderRecord()
    {
        return $this->hasMany(CuttingOrderRecord::class, 'created_by', 'id');
    }

    public function cuttingOrderRecordDetail()
    {
        return $this->hasMany(CuttingOrderRecordDetail::class, 'user_id', 'id');
    }




    

    public function getRoleTitles()
    {
        return $this->roles->pluck('title');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function adminlte_image()
    {
        return url('').'/assets/img/user-profile-default.png';
    }

    public function adminlte_desc()
    {
        return $this->department ? $this->department->department : '-';
    }

    public function adminlte_profile_url()
    {
        return route('profile.index');
    }
}