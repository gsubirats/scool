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

    /** @test */
    public function cannot_remove_upload_files_not_owned()
    {
        $response = $this->json('POST', 'file/remove/from/local', [
            'path' => 'avatar.jpg'
        ]);

        $response->assertStatus(403);

        $response = $this->json('POST', 'file/remove/from/local', [
            'path' => 'uploads/avatar.jpg'
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function remove_upload_file()
    {
        Storage::fake('local');

        $path = Storage::putFile('tenant_test/uploads', UploadedFile::fake()->image('avatar.jpg'));

        $response = $this->json('POST', 'file/remove/from/local', [
            'path' => $path
        ]);

        $response->assertSuccessful();

        // Assert the file was removed...
        Storage::disk('local')->assertMissing($path);

        Storage::fake('public');

        $path = Storage::putFile('tenant_test/uploads', UploadedFile::fake()->image('avatar.jpg'));

        $response = $this->json('POST', 'file/remove/from/public', [
            'path' => $path
        ]);

        $response->assertSuccessful();

        // Assert the file was removed...
        Storage::disk('public')->assertMissing($path);

    }
}
