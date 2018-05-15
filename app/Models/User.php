<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use App\Notifications\ResetPasswordNotification;
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
    use Notifiable,HasApiTokens, HasRoles, FormattedDates;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password' ,
    ];

    protected $appends = ['formatted_created_at','formatted_updated_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return mixed
     */
    public function isSuperAdmin()
    {
        return $this->admin;
    }

    /**
     * Get the user type associated with the user.
     */
    public function type()
    {
        // TODO
    }

    /**
     * Create user if no user with same email exists.
     *
     * @param $data
     * @return mixed
     */
    public static function createIfNotExists($data)
    {
        if (! $user = self::where('email','=',$data['email'])->first()) return self::create($data);
        return $user;
    }


    /**
     * Assign role but not fail if role is already assigned.
     *
     * @param $role
     * @return $this
     */
    public function addRole($role)
    {
        if (!$this->hasRole($role)) $this->assignRole($role);
        return $this;
    }

    /**
     * Get the staffs for the user.
     */
    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    /**
     * Assign staff.
     *
     * @param $staff
     */
    public function assignStaff($staff)
    {
        $this->staffs()->save($staff);
    }

    /**
     * Get the name associated with this username.
     */
    public function fullname()
    {
        return $this->belongsTo(Name::class);
    }

    /**
     * Assign full name.
     *
     * @param Name $name
     * @return $this
     */
    public function assignFullName(Name $name)
    {
        $this->fullname()->associate($name);
        return $this;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
