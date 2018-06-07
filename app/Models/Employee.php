<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee.
 *
 * @package App\Models
 */
class Employee extends Model
{
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
}
