<?php

namespace Tests\Feature\Tenants;

use App\Console\Kernel;
use App\Events\TeacherPhotosZipUploaded;
use App\Events\UnassignedTeacherPhotoUploaded;
use App\Models\User;
use Config;
use Event;
use File;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UnassignedTeacherPhotosControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class UnassignedTeacherPhotosControllerTest extends BaseTenantTest
{
    use RefreshDatabase;

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->artisan('migrate',[
            '--path' => 'database/migrations/tenant'
        ]);

        $this->app[Kernel::class]->setArtisan(null);
    }


    /** @test */
    public function manager_can_upload_zip()
    {
        $this->withoutExceptionHandling();
        Storage::fake('local');
        Event::fake();

        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photos', [
            'photos' => UploadedFile::fake()->create('photos.zip')
        ]);

        $response->assertSuccessful();
        $path = $response->getContent();
        Storage::disk('local')->assertExists($path);

        $this->assertEquals($path,'teacher_photos_zip/photos.zip');

        Event::assertDispatched(TeacherPhotosZipUploaded::class, function ($e) use ($path) {
            return $e->path === $path;
        });
    }

    /** @test */
    public function user_cannot_upload_new_unassigned_teacher_photo()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photos', [
            'teacher_photo' => UploadedFile::fake()->create('photos.zip')
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function upload_new_unassigned_teacher_photo_validation()
    {
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/unassigned_teacher_photo');

        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,"The given data was invalid." );
        $this->assertEquals($result->errors->teacher_photo[0],"The teacher photo field is required." );

        $response = $this->json('POST','/api/v1/unassigned_teacher_photo',[
            'teacher_photo' => 'dsasd'
        ]);

        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,"The given data was invalid." );
        $this->assertEquals($result->errors->teacher_photo[0],"The teacher photo must be an image." );

        $response = $this->json('POST','/api/v1/unassigned_teacher_photo',[
            'teacher_photo' => UploadedFile::fake()->create('prova.pdf')
        ]);

        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,"The given data was invalid." );
        $this->assertEquals($result->errors->teacher_photo[0],"The teacher photo must be an image." );

        $response = $this->json('POST','/api/v1/unassigned_teacher_photo',[
            'teacher_photo' => UploadedFile::fake()->image('photo.jpg')
        ]);

        $response->assertStatus(422);
        $result = json_decode($response->getContent());

        $this->assertEquals($result->message,"The given data was invalid." );
        $this->assertEquals($result->errors->teacher_photo[0],"The teacher photo has invalid image dimensions." );
    }

}
