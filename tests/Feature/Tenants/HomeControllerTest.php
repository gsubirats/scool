<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class HomeControllerTest.
 *
 * @package Tests\Feature
 */
class HomeControllerTest extends BaseTenantTest
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
    public function show_home()
    {
        create_fake_audit_log_entries();
        $user = create(User::class);
        $this->actingAs($user);

        $response = $this->get('/home');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.home');
        $response->assertViewHas('auditLogItems',function ($entries) {
            $entry = $entries->first();
            return check_audit_log_entry($entry);
        });

    }
}
