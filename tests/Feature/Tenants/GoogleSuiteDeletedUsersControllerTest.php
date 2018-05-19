<?php

namespace Tests\Feature\Tenants;

use Illuminate\Contracts\Console\Kernel;
use App\Models\User;
use Config;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GoogleSuiteDeletedUsersControllerTest
 *
 * @package Tests\Feature\Tenants
 */
class GoogleSuiteDeletedUsersControllerTest extends BaseTenantTest
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
     * @group gsuite
     */
    public function show_google_deleted_users()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $manager = create(User::class);
        $this->actingAs($manager,'api');
        $role = Role::firstOrCreate([
            'name' => 'UsersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->json('GET','/api/v1/gsuite/users');
        $response->assertSuccessful();
    }

    /** @test */
    public function user_cannot_show_google_deleted_users()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/gsuite/users/');
        $response->assertStatus(403);
    }

    /**
     * @test
     * @group working
     */
    public function show_google_deleted_user_info()
    {

    }

    /** @test */
    public function user_cannot_show_google_user_info()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $response = $this->json('GET','/api/v1/gsuite/users/sergitur@iesebre.com');
        $response->assertStatus(403);
    }

    /** @test
     * @group working
     */
    public function undelete_google_user()
    {

    }

    /** @test */
    public function user_cannot_undelete_google_user()
    {
        Config::set('google.service.enable', true);
        Config::set('google.service.file', './storage/app/gsuite_service_accounts/scool-07eed0b50a6f.json');
        Config::set('google.admin_email', 'sergitur@iesebre.com');

        $user = create(User::class);
        $this->actingAs($user,'api');

        $otheruser = factory(User::class)->create([
            'email' => 'testy.testerton@iesebre.com'
        ]);
        $response = $this->json('DELETE','/api/v1/gsuite/users/' . $otheruser->email);
        $response->assertStatus(403);
    }

}
