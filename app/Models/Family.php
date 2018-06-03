<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Family.
 *
 * @package App\Models
 */
class Family extends Model
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
     * Get the jobs associated to the family.
     */
    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
