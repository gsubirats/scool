<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use File;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AssignedTeacherPhotoControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class AssignedTeacherPhotoControllerTest extends BaseTenantTest
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

    /**
     * @test
     * @group working
     */
    public function store_teacher_photo_from_available_teacher_photos()
    {
        Storage::fake('local');
//        Event::fake();

        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $files = File::allFiles(base_path('tests/__Fixtures__/photos/teachers'));

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/' . $files[0]->getBasename(),
            $files[0]->getContents()
        );
        dump(str_slug($files[0]->getBasename()));
        $user = factory(User::class)->create();
        $response = $this->json('POST','/api/v1/teacher/' . $user->id . '/photo', [
            'photo' => str_slug($files[0]->getBasename())
        ]);
        $response->assertSuccessful();
//        $response->dump();
    }

    /** @test */
    public function store_teacher_photo_from_available_teacher_photos_validation()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $user = factory(User::class)->create();
        $response = $this->json('POST','/api/v1/teacher/' . $user->id . '/photo');
        $response->assertStatus(422);
    }

    /** @test */
    public function user_cannot_store_teacher_photo_from_available_teacher_photos()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/teacher/1/photo');
        $response->assertStatus(403);
    }
}
