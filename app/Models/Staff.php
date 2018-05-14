<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Staff.
 *
 * @package App\Tenant
 */
class Staff extends Model
{
    use FormattedDates;
    
    protected $guarded = [];

    protected $appends = [
        'formatted_created_at',
        'formatted_updated_at',
        'formatted_created_at_diff',
        'formatted_updated_at_diff'
    ];

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
     * Get the user associated to the staff.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the holder of the place.
     */
    public function holder()
    {
        return $this->belongsTo(User::class);
    }
}
