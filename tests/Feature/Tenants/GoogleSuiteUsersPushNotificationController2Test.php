<?php

namespace Tests\Feature\Tenants;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Class GoogleSuiteUsersPushNotificationController2Test.
 *
 * @package Tests\Feature
 */
class GoogleSuiteUsersPushNotificationController2Test extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_receive_google_suite_users_push_notifications1()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('https://iesebre.scool.iesebre.com/gsuite/notifications',[
            'kind' => "admin#directory#user",
            'id' => 2341412,
            'etag' => 'weqqw4321',
            'primaryEmail' => 'prova@iesebre.com'
        ]);

        $response->assertSuccessful();
        $response->dump();
    }
}
