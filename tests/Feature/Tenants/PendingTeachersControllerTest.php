<?php

namespace Tests\Feature\Tenants;

use App\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class PendingTeachersControllerTest
 * @package Tests\Feature\Tenants
 */
class PendingTeachersControllerTest extends BaseTenantTest
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
    public function users_can_create_pending_teacher()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/add_teacher');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.teacher.show_pending_teacher');
//        $response->assertViewHas('staff');
    }
}
