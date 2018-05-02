<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\StaffType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class StaffTypeTest.
 *
 * @package Tests\Unit
 */
class StaffTypeTest extends TestCase
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
    public function find_staff_type_by_name()
    {
        $this->assertNull(StaffType::findByName('Administratiu/va'));
        $type = StaffType::firstOrCreate([
            'name' => 'Administratiu/va'
        ]);

        $this->assertTrue($type->is(StaffType::findByName('Administratiu/va')));
    }
}
