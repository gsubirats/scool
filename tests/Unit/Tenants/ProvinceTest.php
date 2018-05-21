<?php

namespace Tests\Unit\Tenants;
use App\Models\Province;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ProvinceTest.
 *
 * @package Tests\Unit
 */
class ProvinceTest extends TestCase
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
    public function find_by_name()
    {
        $this->assertNull(Province::findByName('Tarragona'));

        $tarragona = Province::create([
            'name' => 'Tarragona',
            'postalcode' => '43500',
            'postal_code_prefix' => '43',
            'state_id' => 9
        ]);

        $this->assertTrue($tarragona->is(Province::findByName('Tarragona')));
    }

}
