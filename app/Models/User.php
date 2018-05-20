<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Storage;

/**
 * Class User.
 *
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable,HasApiTokens, HasRoles, FormattedDates;

    const DEFAULT_PHOTO = 'default.png';
    const PHOTOS_PATH = 'user_photos';
    const DEFAULT_PHOTO_PATH = self::PHOTOS_PATH . '/' . self::DEFAULT_PHOTO;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password' ,
    ];

    protected $appends = ['formatted_created_at','formatted_updated_at','hashid'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->hashedKey();
    }

    /**
     * Hashed key.
     * @return string
     */
    protected function hashedKey()
    {
        $hashids = new \Hashids\Hashids(config('scool.salt'));
        return $hashids->encode($this->getKey());
    }

    /**
     * @return mixed
     */
    public function isSuperAdmin()
    {
        return $this->admin;
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
     * @return $this
     */
    public function assignStaff($staff)
    {
        $this->staffs()->save($staff);
        return $this;
    }

    /**
     * Assign full name.
     *
     * @param $fullname
     * @return $this
     */
    public function assignFullName($fullname)
    {
        if ($this->person) {
            $this->person->givenName = $fullname['givenName'];
            $this->person->sn1 = $fullname['sn1'];
            $this->person->sn2 = $fullname['sn2'];
        } else {
            $person = Person::create($fullname);
            $person->user_id = $this->id;
            $person->save();
        }
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

    /**
     * Scope a query to only include teacher users.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTeachers($query)
    {
        return $query->role('Teacher')
            ->whereHas('staffs', function ($query) {
                $query->where('type_id','=',StaffType::findByName('Professor/a')->id);
            })->whereHas('staffs', function ($query) {
                $query->where('type_id','=',StaffType::findByName('Professor/a')->id);
        });
    }

    /**
     * Get the initial/proposed photo name.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhotoNameAttribute($value)
    {
        return $this->id . '_' . str_slug($this->name) . '_' . str_slug($this->email);
    }

    /**
     * Get the photo path prefix.
     *
     * @param  string  $value
     * @return string
     */
    public function getPhotoPathAttribute($value)
    {
        return 'user_photos/';
    }

    /**
     * Get the photo path prefix.
     *
     * @param  string  $value
     * @return string
     */
    public function getHashIdAttribute($value)
    {
        return $this->hashedKey();
    }

    /**
     * Obtain photo tenant path.
     *
     * @param $photo
     * @param $tenant
     * @return mixed|string
     */
    public function obtainPhotoTenantPath($photo, $tenant)
    {
        $ext = pathinfo($photo, PATHINFO_EXTENSION);
        $path = preg_replace('#/+#','/',
            $tenant. '/' . $this->photo_path . '/'. $this->photo_name);
        if($ext) {
            $path = $path . '.' . $ext;
        }
        return $path;
    }

    /**
     * Assign photo to user.
     *
     * @param $photo
     * @return $this
     */
    public function assignPhoto($photo,$tenant)
    {
        $path = $this->obtainPhotoTenantPath($photo,$tenant);
        if (starts_with($photo, $storage = Storage::disk('local')->path(''))) {
            $photo = str_after($photo, $storage);
        }
        if(Storage::exists($path)) Storage::delete($path);
        Storage::disk('local')->move($photo, $path);
        //Remove extra slashes from path like user_photos//photo.png
        $this->photo = $path;
        $this->photo_hash = md5($path);
        $this->save();
        return $this;
    }

    /**
     * Unassign photo.
     */
    public function unassignPhoto($destinationPath)
    {
        if(Storage::exists($destinationPath)) Storage::delete($destinationPath);
        Storage::disk('local')->move($this->photo, $destinationPath);
        $this->photo = '';
        $this->photo_hash = '';
        $this->save();
        return $this;
    }

    /**
     * Get the teacher record associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the person record associated with the user.
     */
    public function person()
    {
        return $this->hasOne(Person::class);
    }

    /**
     * Assign teacher to user.
     *
     * @param $teacher
     * @return $this
     */
    public function assignTeacher($teacher)
    {
        $teacher->user_id = $this->id;
        $teacher->save();
        return $this;
    }

}
