<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Teacher.
 *
 * @package App\Models
 */
class Teacher extends Model
{
    protected $guarded = [];

    protected $appends = ['full_search'];

    /**
     * Get the user that owns the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that owns the teacher.
     */
    public function administrativeStatus()
    {
        return $this->belongsTo(AdministrativeStatus::class);
    }

    /**
     * Get the specialty that owns the teacher.
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Find by code.
     *
     * @param $code
     * @return mixed
     */
    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    /**
     * full_search accessor.
     *
     * @return string
     */
    public function getFullSearchAttribute()
    {
        $searchString = $this->code;
        if ($this->user) {
            $searchString = $searchString . ' ' .  $this->user->name . ' ' . $this->user->email;
        }
        if ($this->user && $this->user->person) {
            $searchString = $searchString . ' ' .  $this->user->person->givenName . ' ' . $this->user->person->sn1
                . ' ' . $this->user->person->sn2;
        }
        if ($this->specialty) {
            $searchString = $searchString . ' ' .  $this->specialty->code . ' ' .  $this->specialty->name ;
        }
        if ($this->specialty && $this->specialty->family) {
            $searchString = $searchString . ' ' .  $this->specialty->family->code . ' ' .  $this->specialty->family->name ;
        }
        if ($this->administrativeStatus) {
            $searchString = $searchString . ' ' .  $this->administrativeStatus->name . ' ' .  $this->administrativeStatus->code;
        }
        if ($this->specialty && $this->specialty->family && $this->specialty->jobs) {
            $searchString = $searchString . ' ' .  $this->specialty->family->code . '_' . $this->specialty->code
                . '_' . $this->specialty->jobs[0]->order . '_' . $this->specialty->jobs[0]->code;
        }

        return $searchString;
    }
}
