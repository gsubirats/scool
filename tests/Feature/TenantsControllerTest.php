<?php

namespace Tests\Feature;

use App\Events\TenantCreated;
use App\User;
use Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use View;

/**
 * Class TenantsControllerTest
 * @package Tests\Feature
 */
class TenantsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_user_can_see_create_tenant_form()
    {
        $user = create(User::class);
        $this->actingAs($user);
        View::share('user',$user);

        $response = $this->get('/home');
        $response->assertViewIs('tenant.tenants');
        $response->assertViewHas('tenants');
        $response->assertSuccessful();
    }

    /** @test */
    public function logged_user_can_see_his_tenants()
    {
        $user = create(User::class);

        $user->addTenant(create_tenant('Acme SL','acme'));
        $user->addTenant(create_tenant('Purgas SL','purgas'));
        $user->addTenant(create_tenant('Prova SL','prova'));

        $this->actingAs($user,'api');

        $response = $this->get('/api/v1/tenant');

        $response->assertSuccessful();

        $this->assertCount(3,json_decode($response->getContent()));

        $response->assertJsonFragment(
            [
                'name' => 'Acme SL',
                'subdomain' => 'acme',
                'hostname' => 'localhost',
                'username' => 'acme',
                'database' => 'acme',
                'port' => '3306'
            ],
            [
                'name' => 'Purgas SL',
                'subdomain' => 'purgas',
                'hostname' => 'localhost',
                'username' => 'purgas',
                'database' => 'purgas',
                'port' => '3306'
            ],
            [
                'name' => 'Prova SL',
                'subdomain' => 'prova',
                'hostname' => 'localhost',
                'username' => 'prova',
                'database' => 'prova',
                'port' => '3306'
            ]
        );

        $response->assertJsonMissing(
            [
                'password'
            ]
        );
    }

    /** @test */
    public function logged_user_can_create_tenant()
    {
        $this->withoutExceptionHandling();
        $user = create(User::class);
        $this->actingAs($user,'api');

        Event::fake();

        $response = $this->post('/api/v1/tenant', [
            'name' => 'ACME SL',
            'subdomain' => 'acme'
        ]);

        Event::assertDispatched(TenantCreated::class, function ($e) {
            return $e->tenant->name === 'ACME SL' && $e->tenant->subdomain === 'acme';
        });

        $response->assertSuccessful();
        $this->assertEquals('ACME SL',$user->tenants()->first()->name);
        $this->assertEquals('acme',$user->tenants()->first()->subdomain);
        $this->assertEquals('localhost',$user->tenants()->first()->hostname);
        $this->assertEquals('acme',$user->tenants()->first()->database);
        $this->assertEquals('acme',$user->tenants()->first()->username);
        $this->assertEquals(3306,$user->tenants()->first()->port);
        $this->assertTrue($user->tenants()->first()->password != '');
    }
}
