<?php

namespace Tests\Feature;

use DateTime;
use DB;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class ProvincesControllerTest.
 *
 * @package Tests\Feature
 */
class ProvincesControllerTest extends BaseTenantTest
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

    protected function seed_provinces_sample()
    {
        // Taken from //https://gist.github.com/daguilarm/0e93b73779f0306e5df2
        DB::table('states')->insert([
            ['id' => '9', 'country_code' => "ESP", 'name' => "Cataluña", 'created_at' => new DateTime, 'updated_at' => new DateTime]
        ]);

        DB::table('provinces')->insert([
            ['id' => '33','state_id' => 9, 'postal_code_prefix' => '08' , 'name' => 'Barcelona', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '34','state_id' => 9, 'postal_code_prefix' => '17' , 'name' => 'Girona', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '35','state_id' => 9, 'postal_code_prefix' => '25' , 'name' => 'Lleida', 'created_at' => new DateTime, 'updated_at' => new DateTime],
            ['id' => '36','state_id' => 9, 'postal_code_prefix' => '43' , 'name' => 'Tarragona', 'created_at' => new DateTime, 'updated_at' => new DateTime]
        ]);
    }

    /** @test */
    public function list_provinces()
    {   $this->seed_provinces_sample();
        $response = $this->json('GET','/api/v1/provinces');

        $response->assertSuccessful();

        $response->assertJsonStructure([[
            'id',
            'state_id',
            'name',
            'created_at',
            'updated_at'

        ]]);

        $result = json_decode($response->getContent());

        $this->assertCount(4,$result);


    }
}
