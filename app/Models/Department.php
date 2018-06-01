<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Department.
 *
 * @package App\Models
 */
class Department extends Model
{
    protected $guarded = [];

    /**
     * Find by name.
     *
     * @param $name
     * @return mixed
     */
    public static function findByName($name)
    {
        return static::where('name','=',$name)->first();
    }

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
