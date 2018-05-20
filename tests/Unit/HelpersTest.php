<?php

namespace Tests\Unit;

use File;
use Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class HelpersTest.
 *
 * @package Tests\Unit
 */
class HelpersTest extends TestCase
{
    /** @test */
    public function get_photo_slugs_from_path()
    {
        Storage::fake('local');
        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers'));

        $tenant = 'tenant_test';
        Storage::disk('local')->put(
            $tenant . '/teacher_photos/' . $files[0]->getBasename(),
            $files[0]->getContents()
        );

        $photos = get_photo_slugs_from_path($tenant . '/teacher_photos');
        $this->assertInstanceOf(\Illuminate\Support\Collection::class,$photos);
        $this->assertCount(1,$photos);
        foreach ($photos as $photo) {
            $this->assertTrue(is_array($photo));
            $this->assertTrue(array_key_exists('file',$photo));
            $this->assertInstanceOf('Symfony\Component\Finder\SplFileInfo',$photo['file']);
            $this->assertTrue(array_key_exists('filename',$photo));
            $this->assertTrue(array_key_exists('slug',$photo));
        }
    }

}
