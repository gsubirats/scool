<?php

namespace Tests\Feature\Tenants;

use App\Models\Menu;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BaseTenantTest;

/**
 * Class MenuControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class MenuControllerTest extends BaseTenantTest
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
    public function can_get_menu_items()
    {
        Menu::create($menu1 = [
            'text' => 'menu1'
        ]);

        Menu::create($menu2 = [
              'text' => 'menu2',
              'icon' => 'home'
        ]);

        Menu::create($menu3 = [
                'text' => 'google',
                'icon' => 'link',
                'href' => 'http://www.google.com'
        ]);

        Menu::create($menu4 = [
                'text' => 'home',
                'icon' => 'home',
                'href' => '/home'
        ]);

        $response = $this->get('/api/v1/menu');
        $response->assertSuccessful();

        $this->assertCount(4, json_decode($response->getContent()));
//        $response->dump();

        $response->assertJsonFragment($menu1);
        $response->assertJsonFragment($menu2);
        $response->assertJsonFragment($menu3);
        $response->assertJsonFragment($menu4);

    }


}
