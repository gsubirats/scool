<?php

namespace Tests\Feature\Tenant;

use App\Models\User;
use File;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Storage;
use Tests\BaseTenantTest;
use URL;

/**
 * Class UserPhotoControllerTest.
 *
 * @package Tests\Feature
 */
class UserPhotoControllerTest extends BaseTenantTest
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
    public function user_photo()
    {
        Storage::fake('local');

        Storage::disk('local')->put(
            'tenant_test/user_photos/default.png',
            File::get(base_path('tests/__Fixtures__/photos/users/default.png'))
        );

        $user = factory(User::class)->create();
        $response = $this->get('/user_photo/' . $user->getRouteKey());
        $response->assertSuccessful();
        $this->assertEquals(Storage::disk('local')->path('tenant_test/' . User::DEFAULT_PHOTO_PATH), $response->baseResponse->getFile()->getPathName());
        $this->assertFileEquals(Storage::disk('local')->path('tenant_test/' . User::DEFAULT_PHOTO_PATH), $response->baseResponse->getFile()->getPathName());

        $user2 = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);

        Storage::disk('local')->put(
            'tenant_test/teacher_photos/sergi.jpg',
            File::get(base_path('tests/__Fixtures__/photos/users/sergi.jpg'))
        );

        $user2->assignPhoto('tenant_test/teacher_photos/sergi.jpg','tenant_test');
        $response = $this->get('/user_photo/' . $user2->getRouteKey());

        $this->assertEquals(Storage::disk('local')->path('tenant_test/user_photos/' . $user2->id . '_pepe-pardo-jeans_pepepardo-at-jeanscom.jpg'), $response->baseResponse->getFile()->getPathName());
        $this->assertFileEquals(Storage::disk('local')->path('tenant_test/user_photos/' . $user2->id . '_pepe-pardo-jeans_pepepardo-at-jeanscom.jpg'), $response->baseResponse->getFile()->getPathName());

        $response->assertSuccessful();

    }
}
