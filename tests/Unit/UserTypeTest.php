<?php

namespace Tests\Unit;

use App\Exceptions\UserTypeDoesNotExist;
use App\Models\UserType;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserTypeTest
 * @package Tests\Unit
 */
class UserTypeTest extends TestCase
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
    public function test_find_byName()
    {
        UserType::create(['name' => 'Alumne']);
        $type = UserType::findByName('Alumne');
        $this->assertEquals('Alumne',$type->name);
    }

    /** @test */
    public function test_find_byName_exception()
    {
        try {
            $type = UserType::findByName('Alumne');
        } catch (UserTypeDoesNotExist $e) {
            $this->assertTrue(true);
            return;
        }
        $this->fail('UserTypeDoesNotExist exception has not been throwed');

    }

    /** @test */
    public function test_roles()
    {
        $type = UserType::create(['name' => 'Alumne']);
        $this->assertCount(0,$type->roles);

        $role = Role::create([
            'name' => 'Role1'
        ]);
        $type->roles()->save($role);
        $type = $type->fresh();
        $this->assertCount(1,$type->roles);
        $this->assertEquals('Role1',$type->roles->first()->name);
    }
}
