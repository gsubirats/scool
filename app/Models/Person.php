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
        return fullname($this->givenName, $this->sn1,  $this->sn2);
    }

    /**
     * Get the name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        return name($this->givenName,$this->sn1, $this->sn2);
    }

    /**
     * Find by Identifier.
     *
     * @param $code
     * @return mixed
     */
    public static function findByIdentifier($identifier,$type = null)
    {
        if ($type === null) {
            $type = IdentifierType::findByName('NIF')->id;
        } else {
            if (is_object($type)) $type = $type->id;
            if (is_string($type)) {
                $type = IdentifierType::findByName($type)->id;
            }
        }
        $identifier = Identifier::where('value',$identifier)->where('type_id',$type)->first();
        if(!$identifier) return null;
        return self::where('identifier_id', $identifier->id)->first();
    }
}
