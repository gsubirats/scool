<?php

namespace Tests\Unit\Tenants;

use App\Models\AdministrativeStatus;
use App\Models\Family;
use App\Models\Force;
use App\Models\Specialty;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Teacher;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherTest.
 *
 * @package Tests\Unit
 */
class TeacherTest extends TestCase
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
    public function find_by_code()
    {
        $this->assertNull(Teacher::findByCode('040'));

        $teacher = Teacher::create([
            'code' => '040',
            'user_id' => factory(User::class)->create()->id
        ]);

        $this->assertTrue($teacher->is(Teacher::findByCode('040')));
    }

    /** @test */
    function can_get_full_search_attribute()
    {
        $teacher = Teacher::create([
            'code' => '020',
        ]);

        $this->assertEquals('020', $teacher->full_search);

        Role::firstOrCreate(['name' => 'Teacher']);
        JobType::firstOrCreate([
            'name' => 'Professor/a'
        ]);
        // Informática
        Force::firstOrCreate([
            'name' => "Professors d'ensenyament secundari",
            'code' => 'SECUNDARIA'
        ]);
        Family::firstOrCreate([
            'name' => 'FAMInformàtica',
            'code' => 'INF'
        ]);
        Specialty::firstOrCreate([
            'code' => '507',
            'name' => 'Informàtica',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('INF')->id
        ]);
        AdministrativeStatus::firstOrCreate([
            'name' => 'Funcionari/a amb plaça definitiva',
            'code' => 'FUNCIONARI DEF'
        ]);

        User::createIfNotExists([
            'name' => 'Acacha',
            'email' => 'stur@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignFullName([
                'givenName' => 'Sergi',
                'sn1' => 'Tur',
                'sn2' => 'Badenas',
            ])
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('507')->id,
                    'family_id' => Family::findByCode('INF')->id,
                    'code' => '040',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '040'
            ]))->assignTeacherData([  // Code ius already assigned at initialize_teachers helper
                'administrative_status_id' => AdministrativeStatus::findByName('Funcionari/a amb plaça definitiva')->id,
                'specialty_id' => Specialty::findByCode('507')->id,
                'titulacio_acces' => 'Llicenciatura en Filologia Catalana'
            ]);

        $teacher2 = Teacher::findByCode('040');

        $this->assertEquals('040 Acacha stur@iesebre.com Sergi Tur Badenas 507 Informàtica INF FAMInformàtica Funcionari/a amb plaça definitiva FUNCIONARI DEF INF_507_1_040', $teacher2->full_search);

    }

    /** @test */
    public function first_available_code()
    {
        $this->assertEquals('001',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '001']);

        $this->assertEquals('002',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '002']);

        $this->assertEquals('003',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '004']);

        $this->assertEquals('003',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '003']);

        $this->assertEquals('005',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '005']);
        Teacher::firstOrCreate([ 'code' => '006']);
        Teacher::firstOrCreate([ 'code' => '007']);
        Teacher::firstOrCreate([ 'code' => '008']);
        Teacher::firstOrCreate([ 'code' => '008']);
        Teacher::firstOrCreate([ 'code' => '009']);

        $this->assertEquals('010',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '011']);

        $this->assertEquals('010',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '010']);
        $this->assertEquals('012',Teacher::firstAvailableCode());

        Teacher::firstOrCreate([ 'code' => '080']);
        $this->assertEquals('012',Teacher::firstAvailableCode());
    }
}
