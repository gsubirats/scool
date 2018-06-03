<?php

namespace Tests\Unit\Tenants;

use App\Models\Family;
use App\Models\Force;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Specialty;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class JobTest.
 *
 * @package Tests\Unit
 */
class JobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

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

    protected function create_example_job() {
        JobType::create([
            'name' => 'Professor/a'
        ]);

        Force::create([
            'name' => 'Professors d\'ensenyament secundari',
            'code' => 'SECUNDARIA'
        ]);

        Family::create([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);

        Specialty::create([
            'name' => 'Processos sanitaris',
            'code' => '518',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);

        return Job::create([
            'code' => '002',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => Specialty::findByCode('518')->id,
            'family_id' => Family::findByCode('SANITAT')->id,
            'order' => 1
        ]);
    }

    /** @test */
    public function fullcode_accessor()
    {
        $job = $this->create_example_job();

        $this->assertEquals($job->fullcode,'SANITAT_518_1_002');

        $job2 = Job::create([
            'code' => '003',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => Specialty::findByCode('518')->id,
            'order' => 1
        ]);

        $this->assertEquals($job2->fullcode,'TOTS_518_1_003');

    }

    /** @test */
    public function description_accessor()
    {
        $job = $this->create_example_job();
        $this->assertEquals($job->description,'Plaça num 1 de la família Sanitat, especialitat Processos sanitaris');

        $job2 = Job::create([
            'code' => '003',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => Specialty::findByCode('518')->id,
            'order' => 1
        ]);

        $this->assertEquals($job2->description,'Plaça num 1, especialitat Processos sanitaris');
    }
}
