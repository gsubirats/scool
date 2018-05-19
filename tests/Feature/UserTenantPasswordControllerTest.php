<?php

namespace Tests\Feature;

use App\User;
use Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserTenantPasswordControllerTest
 * @package Tests\Feature
 */
class UserTenantPasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @group working
     */
    public function logged_user_can_update_tenant_admin_user_password()
    {
        // TODO
//        $this->assertTrue(true);
//        return;
        $user = create(User::class);
        $this->actingAs($user,'api');

        $tenant = create_tenant('Acme SL','acme');
        $user->addTenant($tenant);
        $this->assertTrue(Hash::check('secret', $user->password));
        $response = $this->json('PUT','/api/v1/tenant/' . $tenant->id . '/password'  , [
            'password' => 'newsecret',
            'password_confirmation' => 'newsecret'
        ]);

        //TODO

        $response->assertSuccessful();
        $user= $user->fresh();
        // TODO
        $this->assertTrue(Hash::check('newsecret', $user->password));

    }

    /** @test */
    public function logged_user_can_update_tenant_admin_user_password_validation()
    {
        // TODO
//        $this->assertTrue(true);
//        return;
        $user = create(User::class);
        $this->actingAs($user,'api');

        $tenant = create_tenant('Acme SL','acme');
        $user->addTenant($tenant);

        $response = $this->json('PUT','/api/v1/tenant/' . $tenant->id . '/password');
        $response->assertStatus(422);
    }
}
