<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Address.
 *
 * @package Acacha\Relationships\Models
 */
class Address extends Model
{
    protected $guarded = [];

    /**
     * Get the location.
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Get the birthplace.
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
