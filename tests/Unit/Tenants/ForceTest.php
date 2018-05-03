<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Force;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class ForceTest
 * @package Tests\Unit\Tenants
 */
class ForceTest extends BaseTenantTest
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
    public function can_find_force_by_code()
    {
        $this->assertNull(Force::findByCode('SECUNDARIA'));
        $force = Force::firstOrCreate([
            'name' => "Professors d'ensenyament secundari",
            'code' => 'SECUNDARIA'
        ]);
        $this->assertTrue($force->is(Force::findByCode('SECUNDARIA')));
    }
}
