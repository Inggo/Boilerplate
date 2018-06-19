<?php

namespace Inggo\Boilerplate;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\HasActivity;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, HasActivity;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static $logOnlyDirty = true;

    protected static $logAttributes = [
        'name', 'username', 'email', 'password', 'role',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'role',
    ];

    protected $appends = [
        'role_label', 'role_level', 'allowed_roles_to_assign'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAllowedRolesToAssignAttribute($value)
    {
        switch ($this->role) {
            case 'owner':
                return ['owner', 'admin', 'manager', 'user'];
            case 'admin':
                return ['manager', 'user'];
            default:
                return [];
        }
    }

    public function getRoleLabelAttribute($value)
    {
        switch ($this->role) {
            case 'owner':
            case 'manager':
                return ucfirst($this->role);
            case 'admin':
                return 'Administrator';
            default:
                return 'User';
        }
    }

    public function getRoleLevelAttribute($value)
    {
        switch ($this->role) {
            case 'owner':
                return 1000;
            case 'admin':
                return 100;
            case 'manager':
                return 10;
            default:
                return 0;
        }
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isAdmin()
    {
        return $this->isOwner() || $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->isAdmin() || $this->role === 'manager';
    }
}
