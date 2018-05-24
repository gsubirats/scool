<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;
use Session;
use Spatie\Permission\Traits\HasRoles;
use Storage;

/**
 * Class User.
 *
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles, FormattedDates, Impersonate;

    const DEFAULT_PHOTO = 'default.png';
    const PHOTOS_PATH = 'user_photos';
    const DEFAULT_PHOTO_PATH = self::PHOTOS_PATH . '/' . self::DEFAULT_PHOTO;

    protected $guard_name = 'web';

    /**
     * @return bool
     */
    public function impersonatedBy()
    {
        if ($this->isImpersonated()) return User::findOrFail(Session::get('impersonated_by'));
        return null;
    }

    /**
     * @return bool
     */
    public function canImpersonate()
    {
        return $this->isSuperAdmin();
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return !$this->isSuperAdmin();
    }

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
     * Get the jobs for the user.
     */
    public function jobs()
    {
        return $this->belongsToMany(Job::class,'staff');
    }

    /**
     * Assign job.
     *
     * @param $job
     * @return $this
     */
    public function assignJob($job)
    {
        $this->jobs()->save($job);
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
            ->whereHas('jobs', function ($query) {
                $query->where('type_id','=',JobType::findByName('Professor/a')->id);
            })->whereHas('jobs', function ($query) {
                $query->where('type_id','=',JobType::findByName('Professor/a')->id);
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

    /**
     * Assign personal data.
     *
     * @param $data
     * @return $this
     */
    public function assignPersonalData($data)
    {
        if($this->person) {
            $this->person->identifier_id = isset($data['identifier_id']) ? $data['identifier_id'] : null;
            $this->person->birthdate = isset($data['birthdate']) ? $data['birthdate'] : null;
            $this->person->birthplace_id = isset($data['birthplace_id']) ? $data['birthplace_id'] : null;
            $this->person->gender = isset($data['gender']) ? $data['gender'] : null;
            $this->person->mobile = isset($data['mobile']) ? $data['mobile'] : null;
            $this->person->other_mobiles = isset($data['other_mobiles']) ? json_encode(explode(',',$data['other_mobiles'])) : null;
            $this->person->phone = isset($data['phone']) ? $data['phone'] : null;
            $this->person->other_phones = isset($data['other_phones']) ? json_encode(explode(',',$data['other_phones'])) : null;
            $this->person->email = isset($data['email']) ? $data['email'] : null;
            $this->person->other_emails = isset($data['other_emails']) ? json_encode(explode(',',$data['other_emails'])) : null;
            $this->person->notes = isset($data['notes']) ? $data['notes'] : null;
            $this->person->save();
        } else {
            $person = Person::create([
                'identifier_id' => isset($data['identifier_id']) ? $data['identifier_id'] : null,
                'birthdate' => isset($data['birthdate']) ? $data['birthdate'] : null,
                'birthplace_id' => isset($data['birthplace_id']) ? $data['birthplace_id'] : null,
                'gender' => isset($data['gender']) ? $data['gender'] : null,
                'mobile' => isset($data['mobile']) ? $data['mobile'] : null,
                'other_mobiles' => isset($data['other_mobiles']) ? json_encode(explode(',',$data['other_mobiles'])) : null,
                'phone' => isset($data['phone']) ? $data['phone'] : null,
                'other_phones' => isset($data['other_phones']) ? json_encode(explode(',',$data['other_phones'])) : null,
                'email' => isset($data['email']) ? $data['email'] : null,
                'other_emails' => isset($data['other_emails']) ? json_encode(explode(',',$data['other_emails'])) : null,
                'notes' => isset($data['notes']) ? $data['notes']  : null
            ]);
            $person->user_id = $this->id;
            $person->save();
        }
        return $this;
    }

    /**
     * Assign address.
     *
     * @param Address $address
     * @return $this
     */
    public function assignAddress(Address $address)
    {
        if ($this->person) {
            $address->person_id = $this->person->id;
        } else {
            $person = Person::create([
                'user_id' => $this->id
            ]);
            $address->person_id = $person->id;
        }
        $address->save();
        return $this;
    }

    /**
     * Assign teacher data.
     *
     * @param $data
     * @return $this
     */
    public function assignTeacherData($data)
    {
        if ($this->teacher) {
            $this->teacher->administrative_status_id = isset($data['administrative_status_id']) ? $data['administrative_status_id'] : null;
            $this->teacher->specialty_id = isset($data['specialty_id']) ? $data['specialty_id'] : null;
            $this->teacher->titulacio_acces = isset($data['titulacio_acces']) ? $data['titulacio_acces'] : null;
            $this->teacher->altres_titulacions = isset($data['altres_titulacions']) ? $data['altres_titulacions'] : null;
            $this->teacher->idiomes = isset($data['idiomes']) ? $data['idiomes'] : null;
            $this->teacher->altres_formacions = isset($data['altres_formacions']) ? $data['altres_formacions'] : null;
            $this->teacher->perfil_professional = isset($data['perfil_professional']) ? $data['perfil_professional'] : null;
            $this->teacher->data_inici_treball = isset($data['data_inici_treball']) ? $data['data_inici_treball'] : null;
            $this->teacher->data_incorporacio_centre = isset($data['data_incorporacio_centre']) ? $data['data_incorporacio_centre'] : null;
            $this->teacher->data_superacio_oposicions = isset($data['data_superacio_oposicions']) ? $data['data_superacio_oposicions'] : null;
            $this->teacher->save();
        } else {
            $teacher = Teacher::create([
                'code' => $data['code'],
                'administrative_status_id' => isset($data['administrative_status_id']) ? $data['administrative_status_id'] : null,
                'specialty_id' => isset($data['specialty_id']) ? $data['specialty_id'] : null,
                'titulacio_acces' => isset($data['titulacio_acces']) ? $data['titulacio_acces'] : null,
                'altres_titulacions' => isset($data['altres_titulacions']) ? $data['altres_titulacions'] : null,
                'idiomes' => isset($data['idiomes']) ? $data['idiomes'] : null,
                'altres_formacions' => isset($data['altres_formacions']) ? $data['altres_formacions'] : null,
                'perfil_professional' => isset($data['perfil_professional']) ? $data['perfil_professional'] : null,
                'data_inici_treball' => isset($data['data_inici_treball']) ? $data['data_inici_treball'] : null,
                'data_incorporacio_centre' => isset($data['data_incorporacio_centre']) ? $data['data_incorporacio_centre'] : null,
                'data_superacio_oposicions' => isset($data['data_superacio_oposicions']) ? $data['data_superacio_oposicions'] : null,
            ]);
            $teacher->user_id = $this->id;
            $teacher->save();
        }

        return $this;
    }

    /**
     * The positions that belong to the user.
     */
    public function positions()
    {
        return $this->belongsToMany(Position::class);
    }

    /**
     * Assignar una posició a un usuari
     *
     * @param $position
     * @return $this
     */
    public function assignPosition($position)
    {
        $this->positions()->save($position);
        return $this;
    }
}
