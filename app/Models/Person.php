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

    protected $appends = [
        'name',
        'fullname'
    ];

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

    /**
     * Get the address.
     */
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Get the fullname.
     *
     * @param  string  $value
     * @return string
     */
    public function getFullnameAttribute($value)
    {
        $fullname = $this->sn1;
        if ($this->sn2) {
            $fullname = $fullname . ' ' . $this->sn2;
        }
        $fullname = $fullname . ', ' . $this->givenName;
        return trim($fullname);
    }

    /**
     * Get the name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return trim($this->givenName . ' ' . $this->sn1 . ' ' . $this->sn2);
    }
}
