<?php

namespace Tests\Feature;

use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

/**
 * Class JobSubstitutionsControllerTest.
 *
 * @package Tests\Feature
 */
class JobSubstitutionsControllerTest extends BaseTenantTest
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
    public function can_remove_all_substitutions_associated_to_a_job()
    {
        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');
        $job= $this->create_job();
        $this->assertCount(0,$job->substitutes);

        $job->addSubtitute($user = factory(User::class)->create());
        $job->addSubtitute($user2 = factory(User::class)->create());
        $job->addSubtitute($user3 = factory(User::class)->create());
        $job = $job->fresh();
        $this->assertCount(3,$job->substitutes);


        $response = $this->json('DELETE','/api/v1/job/' . $job->id . '/substitutions');
        $response->assertSuccessful();

        $job = $job->fresh();
        $this->assertCount(0,$job->substitutes);
    }

    protected function create_job()
    {
        return Job::create([
            'type_id' => JobType::create(['name' => 'Professor/a'])->id,
            'code' => 'A1',
            'order' => 1
        ]);
    }

    /** @test */
    public function regular_user_cannot_remove_all_substitutions_associated_to_a_job()
    {
        $job = $this->create_job();
        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->json('DELETE','/api/v1/job/' . $job->id . '/substitutions');
        $response->assertStatus(403);
    }

    /** @test */
    public function cannot_remove_all_substitutions_associated_to_an_unexisting_job()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->json('DELETE','/api/v1/job/1/substitutions');
        $response->assertStatus(404);
    }
}
