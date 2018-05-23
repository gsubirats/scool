<?php

namespace App\Models\Traits;
use Carbon\Carbon;

/**
 * Class FormattedDates.
 *
 * @package App\Models\Traits
 */
trait FormattedDates
{
    /**
     * formatted_created_at_date attribute.
     *
     * @return mixed
     */
    public function getFormattedCreatedAtAttribute()
    {
        return optional($this->created_at)->format('h:i:sA d-m-Y');
    }

    /**
     * formatted_updated_at_date attribute.
     *
     * @return mixed
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return optional($this->updated_at)->format('h:i:sA d-m-Y');
    }

    /**
     * formatted_created_at_date_diff attribute.
     *
     * @return mixed
     */
    public function getFormattedCreatedAtDiffAttribute()
    {
        Carbon::setLocale(config('app.locale'));
        return optional($this->created_at)->diffForHumans(Carbon::now());
    }

    /**
     * formatted_updated_at_date_diff attribute.
     *
     * @return mixed
     */
    public function getFormattedUpdatedAtDiffAttribute()
    {
        Carbon::setLocale(config('app.locale'));
        return optional($this->updated_at)->diffForHumans(Carbon::now());
    }
}