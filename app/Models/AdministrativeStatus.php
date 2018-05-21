<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AdministrativeStatus.
 *
 * @package App\Models
 */
class AdministrativeStatus extends Model
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
}
