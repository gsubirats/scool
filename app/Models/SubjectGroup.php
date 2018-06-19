<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SubjectGroup.
 *
 * @package App
 */
class SubjectGroup extends Model
{
    protected $guarded = [];

    /**
     * Find by code.
     *
     * @param $code
     * @return mixed
     */
    public static function findByCode($code)
    {
        return self::where('code', $code)->first();
    }

    /**
     * Get the comments for the blog post.
     */
    public function week_lessons()
    {
        return $this->hasMany(WeekLesson::class)->orderBy('day,start');
    }
}
