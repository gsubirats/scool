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
        'formatted_updated_at_diff',
        'description',
        'fullcode'
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

    /**
     * Get the description.
     *
     * @param  string  $value
     * @return string
     */
    public function getDescriptionAttribute($value)
    {
        $familyName = '';
        if ($this->family) $familyName = ' de la família ' . $this->family->name;
        $specialtyName = '';
        if ($this->specialty) $specialtyName = $this->specialty->name;
        return 'Plaça num ' . $this->order . $familyName . ', especialitat ' . $specialtyName;
    }

    /**
     * Get the full code.
     *
     * @param  string  $value
     * @return string
     */
    public function getFullcodeAttribute($value)
    {
        $familyCode = 'TOTS';
        if ($this->family) $familyCode = $this->family->code;
        $specialtyCode = '';
        if ($this->specialty) $specialtyCode = $this->specialty->code;
        return $familyCode . '_' . $specialtyCode . '_' . $this->order . '_' . $this->code;
    }

}
