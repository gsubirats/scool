<?php

namespace Tests\Feature;

use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SubjectLessonControllerTest.
 *
 * @package Tests\Feature
 */
class SubjectLessonControllerTest extends BaseTenantTest
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
    public function can_see_subject_lessons_management()
    {
        initialize_subjects();
        $manager = create(User::class);
        $this->actingAs($manager);
        $role = Role::firstOrCreate(['name' => 'LessonsManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);

        $response = $this->get('/lessons');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.teachers.show');
        $response->assertViewHas('pendingTeachers');
        $response->assertViewHas('teachers');
        $response->assertViewHas('jobs');
        $response->assertViewHas('specialties');
        $response->assertViewHas('forces');
        $response->assertViewHas('administrativeStatuses');

    }
}
