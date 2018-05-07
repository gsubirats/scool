<?php

namespace App\Listeners;

use Chumper\Zipper\Zipper;

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
        Zipper::make($event->path)->extractTo(dirname($event->path))->close();
    }
}
