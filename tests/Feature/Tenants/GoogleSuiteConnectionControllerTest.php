<?php

namespace Tests\Feature\Tenants;

use App\Console\Kernel;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GSuiteConnectionController.
 *
 * @package Tests\Feature
 */
class GSuiteConnectionControllerTest extends BaseTenantTest
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
    public function can_connect_to_gsuite()
    {
        $this->withExceptionHandling();
        $response = $this->json('GET','/api/v1/gsuite/test_connection');
        $response->dump();
        $response->assertSuccessful();
    }
}
