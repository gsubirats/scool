<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PendingTeacher.
 *
 * @package App\Models
 */
class PendingTeacher extends Model
{
    protected $guarded = [];

    /**
     * Get the teacher specialty.
     */
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
}
