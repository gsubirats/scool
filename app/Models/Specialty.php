<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Specialty.
 *
 * @package App\Models
 */
class Specialty extends Model
{
    protected $guarded = [];

    /**
     * Find by code.
     *
     * @param $code
     * @return mixed
     */
    public static function findByCode($code)
    {
        return static::where('code','=',$code)->first();

    }

    /**
     * Get the job associated to the family.
     */
    public function job()
    {
        return $this->hasMany(Job::class);
    }

    /**
     * Get the force.
     */
    public function force()
    {
        return $this->belongsTo(Force::class);
    }

    /**
     * Get the family.
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
