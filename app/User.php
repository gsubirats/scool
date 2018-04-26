<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class User.
 *
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable,HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Add tenant.
     *
     * @param $tenant
     */
    public function addTenant($tenant)
    {
        $this->tenants()->save($tenant);
        return $tenant;
    }

    /**
     * Get the tenants for .
     */
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function isSuperAdmin()
    {
        return true;
    }
}
