<?php

namespace App\Models;

use App\Revisionable\Revisionable;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JobType.
 *
 * @package App\Models
 */
class JobType extends Revisionable
{
    protected $guarded = [];

    /**
     * Identifiable name.
     *
     * @return string
     */
    public function identifiableName()
    {
        return $this->name;
    }

    /**
     * Find by name.
     *
     * @param $name
     * @return mixed
     */
    public static function findByName ($name) {
        return static::where('name','=',$name)->first();
    }
}
