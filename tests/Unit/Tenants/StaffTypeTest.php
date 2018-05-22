<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\JobType;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class JobTypeTest.
 *
 * @package Tests\Unit
 */
class JobTypeTest extends TestCase
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
        $this->assertNull(JobType::findByName('Administratiu/va'));
        $type = JobType::firstOrCreate([
            'name' => 'Administratiu/va'
        ]);

        $this->assertTrue($type->is(JobType::findByName('Administratiu/va')));
    }
}
