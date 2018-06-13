<?php

namespace Tests\Feature;

use App\Models\JobType;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class AvailableUsersControllerTest.
 *
 * @package Tests\Feature
 */
class AvailableUsersControllerTest extends BaseTenantTest
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
    public function available_users_type_teacher()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        JobType::create([
            'name' => 'Professor/a'
        ]);

        $type = 1;
        $response = $this->json('GET','/api/v1/available-users/' . $type);
        $response->assertSuccessful();
    }

    /** @test */
    public function regular_user_cannot_get_available_users()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        JobType::create([
            'name' => 'Professor/a'
        ]);

        $response = $this->json('GET','/api/v1/available-users/1');
        $response->assertStatus(403);
    }
}
