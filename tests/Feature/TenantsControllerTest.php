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
        $response->assertViewIs('tenant.create');
        $response->assertSuccessful();
    }

    /** @test */
    public function logged_user_can_create_tenant()
    {
        $user = create(User::class);
        $this->actingAs($user);

        Event::fake();

        $response = $this->post('/tenant', [
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
        $this->assertTrue($user->tenants()->first()->password != '');
    }
}
