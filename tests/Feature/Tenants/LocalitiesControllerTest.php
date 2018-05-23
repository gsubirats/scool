<?php

namespace Tests\Feature;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class LocalitiesControllerTest.
 *
 * @package Tests\Feature
 */
class LocalitiesControllerTest extends BaseTenantTest
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

    protected function seed_localities_sample()
    {
        \DB::table('locations')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'TORTOSA',
                    'postalcode' => '43500',
                    'created_at' => '2018-05-15 18:49:56',
                    'updated_at' => '2018-05-15 18:49:56',
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'ROQUETES',
                    'postalcode' => '43520',
                    'created_at' => '2018-05-15 18:49:56',
                    'updated_at' => '2018-05-15 18:49:56',
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'TIVENYS',
                    'postalcode' => '43511',
                    'created_at' => '2018-05-15 18:49:56',
                    'updated_at' => '2018-05-15 18:49:56',
                )
        ));
    }

    /** @test */
    public function list_locations()
    {
        $this->seed_localities_sample();
        $response = $this->json('GET','/api/v1/localities');

        $response->assertSuccessful();
        $response->assertJsonStructure([[
            'id',
            'name',
            'postalcode',
            'created_at',
            'updated_at'

        ]]);

        $result = json_decode($response->getContent());

        $this->assertCount(3,$result);
    }
}
