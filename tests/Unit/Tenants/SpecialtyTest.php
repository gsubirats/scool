<?php

namespace Tests\Unit\Tenants;

use App\Models\Specialty;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SpecialtyTest.
 *
 * @package Tests\Unit\Tenants
 */
class SpecialtyTest extends TestCase
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
        $this->assertNull(Specialty::findByCode('507'));
        $specialty = Specialty::firstOrCreate([
            'code' => '507',
            'name' => 'Informàtica',
            'force_id' => 1
        ]);

        $this->assertTrue($specialty->is(Specialty::findByCode('507')));
    }
}
