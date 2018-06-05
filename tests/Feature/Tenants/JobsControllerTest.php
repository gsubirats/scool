<?php

namespace Tests\Feature\Tenants;

use App\Models\Family;
use App\Models\Specialty;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class JobsControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class JobsControllerTest extends BaseTenantTest
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
    public function show_jobs_management()
    {
        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->get('/jobs');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.jobs.show');
        $response->assertViewHas('jobs');
        $response->assertViewHas('jobTypes');
        $response->assertViewHas('specialties');
        $response->assertViewHas('families');
        $response->assertViewHas('users');
    }

    /** @test */
    public function show_jobs_management_jobs_data()
    {
        $this->withoutExceptionHandling();
        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_departments();
        initialize_teachers();
        initialize_substitutes();

        $response = $this->get('/jobs');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.jobs.show');
        $response->assertViewHas('jobs',function ($jobs) {
            $job = $jobs->first();
            return array_key_exists('id', $job) &&
                array_key_exists('type', $job) &&
                array_key_exists('code', $job) &&
                array_key_exists('holder_hashid', $job) &&
                array_key_exists('holder_code', $job) &&
                array_key_exists('holder_name', $job) &&
                array_key_exists('holder_description', $job) &&
                array_key_exists('active_user_hash_id', $job) &&
                array_key_exists('active_user_code', $job) &&
                array_key_exists('active_user_name', $job) &&
                array_key_exists('active_user_description', $job) &&
                array_key_exists('substitutes', $job) &&
                array_key_exists('fullcode', $job) &&
                array_key_exists('order', $job) &&
                array_key_exists('family', $job) &&
                array_key_exists('family_code', $job) &&
                array_key_exists('family_description', $job) &&
                array_key_exists('specialty', $job) &&
                array_key_exists('specialty_code', $job) &&
                array_key_exists('specialty_description', $job) &&
                array_key_exists('formatted_created_at_diff', $job) &&
                array_key_exists('formatted_created_at', $job) &&
                array_key_exists('formatted_updated_at', $job) &&
                array_key_exists('formatted_updated_at_diff', $job) &&
                array_key_exists('notes', $job);
        });
    }

    /** @test */
    public function regular_user_not_authorized_to_show_jobs_management()
    {
        $user = create(User::class);
        $this->actingAs($user);
        Config::set('auth.providers.users.model', User::class);
        $response = $this->get('/jobs');
        $response->assertStatus(403);
    }

    /** @test */
    public function add_job()
    {
        $this->withoutExceptionHandling();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $this->assertCount(0, Job::all());
        $response = $this->json('POST','/api/v1/jobs', [
            'code' => '040',
            'type' => 'Professor/a',
            'family' => 1,
            'specialty' => 1,
            'holder' => 1,
            'order' => 1,
            'notes' => 'Prova a veure que tal'
        ]);
        $response->assertSuccessful();

        $this->assertCount(1, Job::all());

        $job = Job::find(1);
        $this->assertEquals('040', $job->code);
        $this->assertEquals('Prova a veure que tal',$job->notes);
        $this->assertEquals('Professor/a', JobType::find($job->type_id)->name);
        $this->assertEquals(1, $job->users()->first()->id);
        $this->assertEquals(1, $job->family_id);
        $this->assertEquals('Sanitat', Family::find($job->family_id)->name);
        $this->assertEquals('Processos diagnòstics clínics i productes ortoprotètics', Specialty::find($job->specialty_id)->name);

    }

    /** @test */
    public function add_job_validation()
    {
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $response = $this->json('POST','/api/v1/jobs', [

        ]);
        $result = json_decode($response->getContent());
        $response->assertStatus(422);
        $this->assertEquals('The given data was invalid.',$result->message);
        $this->assertEquals('El camp code és obligatori.',$result->errors->code[0]);
        $this->assertEquals('El camp type és obligatori.',$result->errors->type[0]);

        $response = $this->json('POST','/api/v1/jobs', [
          'code' => '040',
          'type' => 'Professor/a'
        ]);
        $result = json_decode($response->getContent());
        $response->assertStatus(422);
        $this->assertEquals('The given data was invalid.',$result->message);
        $this->assertEquals('El camp family és obligatori quan type és Professor/a.',$result->errors->family[0]);
        $this->assertEquals('El camp specialty és obligatori quan type és Professor/a.',$result->errors->specialty[0]);


    }

    /** @test */
    public function user_cannot_add_job()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->json('POST','/api/v1/jobs', [

        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function remove_job()
    {
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        $staffManager = create(User::class);
        $role = Role::firstOrCreate(['name' => 'StaffManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);
        $this->actingAs($staffManager,'api');

        $job = Job::create([
            'code' => '040',
            'type_id' => $type = JobType::findByName('Professor/a')->id,
            'specialty_id' => 1,
            'family_id' => 1,
            'order' => 1,
            'notes' => 'bla bla bla'
        ]);

        $this->assertCount(1, Job::all());
        $response = $this->json('DELETE','/api/v1/jobs/' . $job->id);
        $response->assertSuccessful();

        $this->assertCount(0, Job::all());
        $result = json_decode($response->getContent());

        $this->assertEquals('040',$result->code);
        $this->assertEquals($type,$result->type_id);
        $this->assertEquals(1,$result->specialty_id);
        $this->assertEquals(1,$result->family_id);
        $this->assertEquals('bla bla bla',$result->notes);

        $result =  json_encode($response->getContent());

    }

    /** @test */
    public function user_cannot_remove_jobs()
    {
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        $user = create(User::class);
        $this->actingAs($user,'api');

        $job = Job::create([
            'code' => '040',
            'type_id' => JobType::findByName('Professor/a')->id,
            'specialty_id' => 1,
            'family_id' => 1,
            'order' => 1,
            'notes' => 'bla bla bla'
        ]);

        $response = $this->json('DELETE','/api/v1/jobs/' . $job->id);
        $response->assertStatus(403);
    }
}
