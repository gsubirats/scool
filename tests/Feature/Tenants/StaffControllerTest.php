<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class StaffControllerTest
 * @package Tests\Feature\Tenants
 */
class StaffControllerTest extends BaseTenantTest
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
    public function show_staff_management()
    {
        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->get('/staff');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.staff.show');
        $response->assertViewHas('staff');
        $response->assertViewHas('staffTypes');
        $response->assertViewHas('specialties');
        $response->assertViewHas('families');
        $response->assertViewHas('users');
    }

    /** @test */
    public function regular_user_not_authorized_to_show_staff_management()
    {
        $user = create(User::class);
        $this->actingAs($user);
        Config::set('auth.providers.users.model', User::class);
        $response = $this->get('/staff');
        $response->assertStatus(403);
    }
}
