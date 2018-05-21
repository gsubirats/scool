<?php

namespace Tests\Unit\Tenants;

use App\Models\Family;
use App\Models\Force;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Location;
use App\Models\Name;
use App\Models\Specialty;
use App\Models\Staff;
use App\Models\StaffType;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
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

        $result = $user->assignStaff(
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
        $this->assertInstanceOf(User::class,$result);

    }

    /** @test */
    public function can_assign_fullname()
    {
        $user = factory(User::class)->create();
        $this->assertNull($user->fullname);

        $result = $user->assignFullName([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => '',
        ]);
        $user = $user->fresh();
        $this->assertEquals($user->person->givenName,'Pepe');
        $this->assertEquals($user->person->sn1,'Pardo');
        $this->assertEquals($user->person->sn2,'');
        $this->assertTrue($user->is($result));
    }

    /** @test */
    public function can_get_teachers()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_staff_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_teachers();

        $teachers = User::teachers()->get();

        $count = $teachers->count();
        // Teachers are users with role teacher and staff assigned as staff_type teacher
        foreach ($teachers as $teacher) {
            $this->assertTrue($teacher->hasRole('Teacher'));
            $this->assertNotNull($teacher->staffs->firstWhere('type_id', StaffType::findByName('Professor/a')->id));
            $this->assertNotNull($teacher->teacher);
        }

        $user = create(User::class);

        $user->assignRole(Role::findByName('Teacher'));
        $teachers = User::teachers()->get();

        $newCount = $teachers->count();
        // Assert being a teacher is not enough you also have to be assigned a staff place of type teacher
        $this->assertEquals($newCount,$count);
    }

    /** @test */
    public function user_initial_photo_name()
    {
        $user = create(User::class);

        $this->assertEquals($user->photoName,
            $user->id . '_' . str_slug($user->name) . '_' . str_slug($user->email));
    }

    /** @test */
    public function user_photos_path()
    {
        $this->assertEquals(User::PHOTOS_PATH,'user_photos');
    }

    /** @test */
    public function can_assign_photo_to_user_with_partial_path()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar.jpg');
        $photo = Storage::disk('local')->putFile('teacher_photos',$fakeImage);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $this->assertNull($user->photo);
        $user->assignPhoto($photo,'tenant_test');
        $this->assertEquals($user->photo,'tenant_test/user_photos/1_pepe-pardo-jeans_pepepardo-at-jeanscom.jpeg');
        $this->assertEquals($user->photo_hash,'c4b959112f6f1d4933dc4506e14338c1');
        $this->assertTrue(Storage::disk('local')->exists($user->photo));

    }

    /** @test */
    public function can_assign_photo_to_user_with_full_path()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar2.jpg');
        $photo2 = Storage::disk('local')->putFile('teacher_photos',$fakeImage);
        $user = factory(User::class)->create([
            'name' => 'Pepa Parda Jeans',
            'email' => 'pepaparda@jeans.com'
        ]);
        $this->assertNull($user->photo);
        $photo2 = Storage::disk('local')->path($photo2);
        $user->assignPhoto($photo2,'tenant_test');
        $this->assertEquals($user->photo,'tenant_test/user_photos/1_pepa-parda-jeans_pepaparda-at-jeanscom.jpeg');
        $this->assertEquals($user->photo_hash,'b3db77742683fb84aa526443af8e85bb');
        $this->assertTrue(Storage::disk('local')->exists($user->photo));
    }

    /** @test */
    public function can_unassign_photo_to_user()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar.jpg');
        $photo = Storage::disk('local')->putFile('tenant_test/teacher_photos',$fakeImage);

        $user = factory(User::class)->create([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@jeans.com'
        ]);
        $this->assertNull($user->photo);
        $user->assignPhoto($photo,'tenant_test');
        $destPath = 'tenant_test/teacher_photos/' . $user->photo_name . '.jpg';
        $user->unassignPhoto($destPath);

        $user = $user->fresh();
        $this->assertEquals($user->photo,'');
        $this->assertEquals($user->photo_hash,'');
        $this->assertFalse(Storage::disk('local')->exists($user->photo));
        $this->assertTrue(Storage::disk('local')->exists($destPath));
    }

    /** @test */
    public function users_have_a_default_photo()
    {
        $this->assertEquals(User::DEFAULT_PHOTO,'default.png');
        $this->assertEquals(User::DEFAULT_PHOTO_PATH,'user_photos/default.png');
        $this->assertEquals(User::PHOTOS_PATH,'user_photos');
    }

    /** @test */
    public function hash_id()
    {
        $user = factory(User::class)->create();
        $hashids = new \Hashids\Hashids(config('scool.salt'));
        $this->assertEquals($user->hashid,$hashids->encode($user->getKey()));
    }

    /** @test */
    public function user_can_have_a_photo()
    {
        Storage::fake('local');

        $fakeImage = UploadedFile::fake()->image('avatar.jpg');

//        Storage::disk('local')->put('file.txt', 'Contents');
        Storage::disk('local')->putFile('prova',$fakeImage);


        $user = create(User::class);
        $this->assertNull($user->photo);

        $path = $user->photoPath . $user->photoName;
        $user->photo = $path;
        $user->save();

        $user= User::find($user->id);
        $this->assertEquals($user->photo,$path);
    }

    /** @test */
    public function assign_teacher_to_user()
    {
        $user = create(User::class);
        $result = $user->assignTeacher($teacher = Teacher::create([
            'code' => '040'
        ]));
        $this->assertTrue($teacher->is($user->teacher));
        $this->assertInstanceOf(User::class,$result);
    }

    /** @test */
    public function can_assign_personal_data_to_user()
    {
        $user = create(User::class);

        seed_identifier_types();
        $nif = IdentifierType::findByName('NIF')->id;

        $tortosa = Location::create([
            'name' => 'TORTOSA',
            'postalcode' => '43500'
        ]);
        $identifier = Identifier::firstOrCreate([
            'value' => '28444026H',
            'type_id' => $nif
        ]);
        $identifierId = $identifier->id;
        $location = Location::findByName('TORTOSA');
        $locationId = $location->id;
        $this->assertNull($user->person);
        $user->assignPersonalData([
            'identifier_id' => $identifierId,
            'birthdate' => Carbon::parse('1988-03-02'),
            'birthplace_id' => $locationId,
            'gender' => 'Home',
            'mobile' => '679578437',
            'other_mobiles' => '645192821,645192822',
            'phone' => '977500949',
            'other_phones' => '9677508695,977500949',
            'notes' => "Coordinador d'informàtica"
        ]);
        $user = $user->fresh();
        $this->assertNotNull($user->person);
        $this->assertEquals($user->person->identifier_id , $identifierId);
        $this->assertEquals($user->person->birthdate , '1988-03-02 00:00:00');
        $this->assertEquals($user->person->birthplace_id , $locationId);
        $this->assertEquals($user->person->gender , 'Home');
        $this->assertEquals($user->person->mobile , '679578437');
        $this->assertEquals($user->person->other_mobiles , '["645192821","645192822"]');
        $this->assertEquals($user->person->phone , '977500949');
        $this->assertEquals($user->person->other_phones , '["9677508695","977500949"]');
        $this->assertEquals($user->person->notes , "Coordinador d'informàtica");
        $this->assertTrue($identifier->is($user->person->identifier , $identifierId));
        $this->assertTrue($location->is($user->person->birthplace , $location));

    }

}
