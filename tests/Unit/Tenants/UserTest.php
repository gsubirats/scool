<?php

namespace Tests\Unit\Tenants;

use App\Models\Family;
use App\Models\Force;
use App\Models\Specialty;
use App\Models\Staff;
use App\Models\StaffType;
use App\Models\User;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class UserTest.
 *
 * @package Tests\Unit
 */
class UserTest extends TestCase
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
    function can_get_formatted_created_at_date()
    {
        $user = factory(User::class)->make([
            'created_at' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $user->formatted_created_at);
    }

    /** @test */
    function can_get_formatted_updated_at_date()
    {
        $user = factory(User::class)->make([
            'updated_at' => Carbon::parse('2016-12-01 8:00pm'),
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $user->formatted_updated_at);
    }

    /** @test */
    function can_create_user_if_not_exists()
    {
        $user = User::createIfNotExists([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);

        $this->assertEquals('Pepe Pardo Jeans', $user->name);
        $this->assertEquals('pepepardo@jeans.com', $user->email);
        $this->assertEquals('$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',$user->password);
        $this->assertEquals('Pepe Pardo Jeans', $user->name);

        $newUser = User::createIfNotExists([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);

        $this->assertTrue($newUser->is($user));
    }

    /** @test */
    function can_add_role()
    {
        $user = factory(User::class)->create();

        $role = Role::create([
            'name' => 'Rol1',
        ]);

        $user->addRole($role);
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($role));

        $user->addRole($role);
        $user = $user->fresh();
        $this->assertTrue($user->hasRole($role));
    }

    /** @test */
    function can_assign_staff()
    {
        $user = factory(User::class)->create();

        StaffType::create(['name' => 'Professor/a']);
        Force::create([
            'name' => 'Secundària',
            'code' => 'SECUNDARIA'
        ]);

        $family = Family::create([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);
        Specialty::create([
            'name' => 'Informàtica',
            'code' => '507',
            'force_id' => Force::findByCode('SECUNDARIA'),
            'family_id' => $family->id
        ]);


        $this->assertCount(0,$user->staffs);

        $user->assignStaff(
            Staff::firstOrCreate([
                'code' => '01',
                'type_id' => StaffType::findByName('Professor/a')->id,
                'specialty_id' => Specialty::findByCode('507')->id,
                'family_id' => Family::findByCode('SANITAT')->id,
            ]));
        $user = $user->fresh();
        $this->assertCount(1,$user->staffs);
        $staff = $user->staffs()->first();
        $this->assertEquals('01',$staff->code);
        $this->assertEquals('Professor/a',$staff->type->name);
        $this->assertEquals('507',$staff->specialty->code);
        $this->assertEquals('SANITAT',$staff->family->code);


    }

}
