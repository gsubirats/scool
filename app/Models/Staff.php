<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Staff.
 *
 * @package App\Tenant
 */
class Staff extends Model
{
    protected $guarded = [];

    /**
     * Get the staff type
     */
    public function type()
    {
        return $this->belongsTo(StaffType::class);
    }

    /**
     * Get the staff family
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the staff specialty
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the holder of the place.
     */
    public function holder()
    {
        return $this->belongsTo(User::class);
    }
}
