<?php

namespace Tests\Feature\Tenants;

use App\Models\Family;
use App\Models\Force;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Specialty;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherProfileControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class TeacherProfileControllerTest extends BaseTenantTest
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
    public function teacher_profile()
    {
        Role::firstOrCreate(['name' => 'Teacher']);
        JobType::firstOrCreate([
            'name' => 'Professor/a'
        ]);
        Family::firstOrCreate([
            'name' => 'Sanitat',
            'code' => 'SANITAT'
        ]);
        Force::firstOrCreate([
            'name' => "Professors d'ensenyament secundari",
            'code' => 'SECUNDARIA'
        ]);
        Specialty::firstOrCreate([
            'code' => 'CAS',
            'name' => 'Castellà',
            'force_id' => Force::findByCode('SECUNDARIA')->id,
            'family_id' => Family::findByCode('SANITAT')->id
        ]);
        $teacher = User::createIfNotExists([
            'name' => 'Pepe Pardo Jeans',
            'email' => 'pepepardo@iesebre.com',
            'password' => 'e5e9fa1ba31ecd1ae84f75caaa474f3a663f05f4', // secret
            'remember_token' => str_random(10),
        ])->addRole(Role::findByName('Teacher'))
            ->assignJob(
                Job::firstOrCreate([
                    'type_id' => JobType::findByName('Professor/a')->id,
                    'specialty_id' => Specialty::findByCode('CAS')->id,
                    'family_id' => Family::findByCode('SANITAT')->id,
                    'code' => '002',
                    'order' => 1
                ])
            )->assignTeacher(Teacher::firstOrCreate([
                'code' => '002'
            ]));
        $teacher = $teacher->fresh();
        $this->actingAs($teacher);
        $response = $this->json('GET','/teacher/profile/');

        $response->assertSuccessful();
    }

    /** @test */
    public function not_teachers_cannot_see_teacher_profile()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $response = $this->json('GET','/teacher/profile/');

        $response->assertStatus(403);
    }
}
