<?php

namespace Tests\Unit\Tenants;

use App\Models\Department;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class DepartmentTest.
 *
 * @package Tests\Unit
 */
class DepartmentTest extends TestCase
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

    /** @test */
    public function can_find_by_code()
    {
        $this->assertEmpty(Department::findByCode('SERVEIS'));
        $deparment = Department::firstOrCreate([
            'name' => 'Departament de serveis socioculturals i a la comunitat',
            'shortname' => 'Serveis socioculturals i a la comunitat',
            'code' => 'SERVEIS',
            'order' => 9,
            'head' => 1
        ]);
        $this->assertTrue($deparment->is(Department::findByCode('SERVEIS')));
    }

    /** @test */
    public function can_find_by_name()
    {
        $this->assertEmpty(Department::findByName('Departament de serveis socioculturals i a la comunitat'));
        $deparment = Department::firstOrCreate([
            'name' => 'Departament de serveis socioculturals i a la comunitat',
            'shortname' => 'Serveis socioculturals i a la comunitat',
            'code' => 'SERVEIS',
            'order' => 9,
            'head' => 1
        ]);
        $this->assertTrue($deparment->is(Department::findByName('Departament de serveis socioculturals i a la comunitat')));
    }
}
