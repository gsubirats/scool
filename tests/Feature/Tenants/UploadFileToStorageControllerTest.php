<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UploadFileToStorageControllerTest.
 *
 * @package Tests\Feature
 */
class UploadFileToStorageControllerTest extends BaseTenantTest
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
    public function upload_file()
    {
        $this->withoutExceptionHandling();
        Storage::fake('local');

        $response = $this->json('POST', 'file/upload/to/local', [
            'file' => UploadedFile::fake()->image('avatar.jpg')
        ]);

        $response->assertSuccessful();
        $path = $response->getContent();

        // Assert the file was stored...
        Storage::disk('local')->assertExists($path);
        $this->assertContains('tenant_test/uploads',$path);

        Storage::fake('public');

        $response = $this->json('POST', '/file/upload/to/public', [
            'file' => UploadedFile::fake()->image('avatar2.jpg')
        ]);

        $response->assertSuccessful();
        $path = $response->getContent();

        // Assert the file was stored...
        Storage::disk('public')->assertExists($path);
        $this->assertContains('tenant_test/uploads',$path);

    }
}
