<?php

namespace App\Models;

use App\Revisionable\RevisionableTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee.
 *
 * @package App\Models
 */
class Employee extends Model
{
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;

    /**
     * Identifiable name.
     *
     * @return string
     */
    public function identifiableName()
    {
        return 'Empleat: ' . $this->fullcode;
    }

    /**
     * Locale name.
     *
     * @return string
     */
    public function localeName()
    {
        return "l'empleat";
    }

    /**
     * Locale identifier.
     *
     * @return mixed
     */
    public function localeIdentifier()
    {
        return $this->fullcode;
    }

    public static function boot()
    {
        parent::boot();
    }

    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'start_at',
        'end_at'
    ];

    /**
     * Get the full code.
     *
     * @param  string  $value
     * @return string
     */
    public function getFullcodeAttribute($value)
    {
        return 'todo fullcode';
    }
}
