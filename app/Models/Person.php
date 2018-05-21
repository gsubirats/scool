<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Person
 *
 * @package Acacha\Relationships\Models
 */
class Person extends Model
{
    protected $guarded = [];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the identifier
     */
    public function identifier()
    {
        return $this->belongsTo(Identifier::class);
    }

    /**
     * Get the birthplace.
     */
    public function birthplace()
    {
        return $this->belongsTo(Location::class);
    }
}
