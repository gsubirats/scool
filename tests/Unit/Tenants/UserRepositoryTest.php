<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Config;
use Illuminate\Contracts\Console\Kernel;
use stdClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserRepositoryTest
 * @package Tests\Unit
 */
class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();
        Config::set('auth.providers.users.model',User::class);
    }

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
    public function store_user()
    {
        $userRepository = new UserRepository();

        $user = new Stdclass();
        $user->name = 'Pepe Pardo Jeans';
        $user->email = 'pepepardo@jeans.com';
        $userEloquent = $userRepository->store($user);
        $this->assertInstanceOf(User::class, $userEloquent);
        $this->assertEquals('Pepe Pardo Jeans', $userEloquent->name);
        $this->assertEquals('pepepardo@jeans.com', $userEloquent->email);
        $this->assertEquals('40', strlen($userEloquent->password));
        $this->assertEquals(null, $userEloquent->admin);
        $this->assertEquals(null, $userEloquent->photo);
        $this->assertEquals(null, $userEloquent->photo_hash);

        $user = new Stdclass();
        $user->name = 'Pepa Parda Jeans';
        $user->email = 'pepaparda@jeans.com';
        $user->password = 'secret';
        $user->photo = 'tenant_test/iesebre/user_photos/photo.png';
        $userEloquent = $userRepository->store($user);
        $this->assertInstanceOf(User::class, $userEloquent);
        $this->assertEquals('Pepa Parda Jeans', $userEloquent->name);
        $this->assertEquals('pepaparda@jeans.com', $userEloquent->email);
        $this->assertEquals('40', strlen($userEloquent->password));
        $this->assertEquals('e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', $userEloquent->password);
        $this->assertEquals(null, $userEloquent->admin);
        $this->assertEquals('tenant_test/iesebre/user_photos/photo.png', $userEloquent->photo);
        $this->assertEquals('704538517ce7cb759765d729de270710', $userEloquent->photo_hash);
    }
}
