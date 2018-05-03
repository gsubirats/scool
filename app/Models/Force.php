<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Force.
 *
 * @package App\Models
 */
class Force extends Model
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
}
