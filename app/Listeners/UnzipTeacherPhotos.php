<?php

namespace App\Listeners;

use Storage;
use Zipper;

/**
 * Class UnzipTeacherPhotos.
 *
 * @package App\Listeners
 */
class UnzipTeacherPhotos
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $zipper = Zipper::make(Storage::path('teacher_photos_zip/teachers.zip'));
        $zipper->extractTo(Storage::path('teacher_photos'));
        $zipper->close();
    }
}
