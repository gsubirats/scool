<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Family;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class FamilyTest.
 *
 * @package Tests\Unit\Tenants
 */
class FamilyTest extends TestCase
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
    public function find_specialty_by_code()
    {
        $this->assertNull(Family::findByCode('INF'));
        $family = Family::firstOrCreate([
            'code' => 'INF',
            'name' => 'Informàtica'
        ]);

        $this->assertTrue($family->is(Family::findByCode('INF')));
    }
}
