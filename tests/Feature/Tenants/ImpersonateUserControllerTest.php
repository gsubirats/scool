<?php

namespace Tests\Feature\Tenants;

use App\Models\User;
use Auth;
use Illuminate\Contracts\Console\Kernel;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ImpersonateUserControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class ImpersonateUserControllerTest extends BaseTenantTest
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
    public function admin_user_can_impersonate_user()
    {
        $admin = factory(User::class)->create([
            'admin' => true,
            'name' => 'Pepe Pardo Jeans'
        ]);
        $this->actingAs($admin,'api');
        dump(Auth::user()->name);

        $user = factory(User::class)->create([
            'name' => 'Pepa Parda'
        ]);

        $response = $this->post('admin/impersonate/user',[
            'user' => $user->id
        ]);

        $response->assertSuccessful();
        dump(Auth::user()->name);
        $this->assertTrue($user->is(Auth::user()));
    }

    /** @test */
    public function admin_user_can_impersonate_user_validation()
    {
        $admin = factory(User::class)->create([
            'admin' => true
        ]);
        $this->actingAs($admin);

        $response = $this->post('admin/impersonate/user');

        $response->assertStatus(422);
    }

    /** @test */
    public function regular_user_cannot_impersonate_user()
    {
        $admin = factory(User::class)->create();
        $this->actingAs($admin);

        $response = $this->post('admin/impersonate/user');

        $response->assertStatus(403);
    }
}
