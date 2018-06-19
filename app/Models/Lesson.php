<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Lesson.
 *
 * @package App\Models
 */
class Lesson extends Model
{
    protected $guarded = [];

    /**
     * Get the subject that owns the lesson.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

}
