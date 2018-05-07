<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\Testing\File;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherPhotosControllerTest.
 *
 * @package Tests\Feature
 */
class TeacherPhotosControllerTest extends BaseTenantTest
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
    public function regular_user_cannot_see_teacher_photos_management()
    {
        $user = create(User::class);
        $this->actingAs($user);
        $response = $this->get('/teachers_photos');

        $response->assertStatus(403);
    }

    /** @test */
    public function manager_teacher_photos_can_see_teacher_photos_management()
    {
        $photoTeachersManager = create(User::class);
        $this->actingAs($photoTeachersManager);
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);

        $response = $this->get('/teachers_photos');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.teachers.photos.show');
//        $response->assertViewHas('staff');

    }

    /** @test */
    function regular_user_cannot_upload_teachers_photos()
    {
        $response = $this->actingAs(factory(User::class)->create(), 'api')
            ->json('POST','/api/v1/teachers_photos', [
                'photos' => File::create('photos.zip'),
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    function teacher_photos_are_uploaded()
    {
        $this->withoutExceptionHandling();
        Storage::fake('teacher_photos');
        $photoTeachersManager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'PhotoTeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $photoTeachersManager->assignRole($role);
        $this->actingAs($photoTeachersManager, 'api');
        $response = $this->json('POST','/api/v1/teachers_photos', [
            'teacher_photos' => File::create('photos.zip'),
        ]);

        $response->assertSuccessful();


//        Storage::disk('teacher_photos')->assertExists('TODO_PATH');
//        Storage::disk('teacher_photos')->assertExists(Concert::first()->poster_image_path);
    }
}
