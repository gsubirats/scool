<?php

namespace App\Models;

use App\Models\Traits\FormattedDates;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Job.
 *
 * @package App\Tenant
 */
class Job extends Model
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
     * Get the job type
     */
    public function type()
    {
        return $this->belongsTo(JobType::class);
    }

    /**
     * Get the job family
     */
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    /**
     * Get the job specialty
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    /**
     * Get the user associated to the job.
     */
    public function users()
    {
        return $this->belongsToMany(User::class,'employees')->as('employee')->withPivot('start_at', 'end_at')->withTimestamps();
    }

    /**
     * Get the holder of the place.
     */
    public function holder()
    {
        return $this->belongsTo(User::class);
    }
}
