<?php

namespace Tests\Unit;

use App\Events\TeacherPhotosZipUploaded;
use File;
use Storage;
use Tests\TestCase;

/**
 * Class UnzipTeacherPhotosTest.
 *
 * @package Tests\Unit
 */
class UnzipTeacherPhotosTest extends TestCase
{
    /** @test */
    public function teacher_photos_are_unzipped_after_event()
    {
        Storage::fake('local');
        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers_zip'));
        Storage::disk('local')->putFileAs('teacher_photos_zip', $files[0], 'teachers.zip');

        event(new TeacherPhotosZipUploaded('teacher_photos_zip/teachers.zip'));

        Storage::disk('local')->assertExists('teacher_photos/40 - TUR, Sergi.jpg');
        Storage::disk('local')->assertExists('teacher_photos/41 - Pardo, Jeans.jpg');
        Storage::disk('local')->assertExists('teacher_photos/42 - Parda, Jeans Parda.jpg');
    }
}
