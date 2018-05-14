<?php

namespace Tests\Feature;

use App\User;
use Config;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserTenantTestControllerTest.
 *
 * @package Tests\Feature
 */
class UserTenantTestControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_test_tenant_connection()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $user->addTenant($tenant = create_tenant('Acme SL','test_' . str_random()));
        delete_mysql_full_database(
            $tenant->database,
            $tenant->username ,
            $tenant->hostname);
        create_mysql_database($tenant->database);
        $password = create_mysql_user($tenant->username, null, null);
        mysql_grant_privileges($tenant->username, $tenant->database, null);
        $tenant->password = $password;
        $tenant->save();

        $response = $this->get('/api/v1/tenant/' . $tenant->id . '/test');

        $response->assertSuccessful();
        $response->assertJsonFragment(['connection' => 'ok']);

        delete_mysql_full_database(
            $tenant->database,
            $tenant->username ,
            $tenant->hostname);
    }

    /** @test */
    public function user_test_tenant_connection_fails_with_unexisting_connection()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');

        $user->addTenant($tenant = create_tenant('Acme SL','test_' . str_random()));

        $response = $this->get('/api/v1/tenant/' . $tenant->id . '/test');

        $response->assertSuccessful();
        $response->assertJsonFragment(['connection' => 'Error']);
    }
}
