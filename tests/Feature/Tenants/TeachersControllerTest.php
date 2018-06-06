<?php

namespace Tests\Feature\Tenants;

use App\Models\IdentifierType;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeachersControllerTest.
 *
 * @package Tests\Feature\Tenants
 */
class TeachersControllerTest extends BaseTenantTest
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
    public function show_teachers_management()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_departments();
        initialize_teachers();

        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->get('/teachers');

        $response->assertSuccessful();
        $response->assertViewIs('tenants.teachers.show');
        $response->assertViewHas('pendingTeachers');
        $response->assertViewHas('teachers');
        $response->assertViewHas('jobs');
        $response->assertViewHas('specialties');
        $response->assertViewHas('forces');
        $response->assertViewHas('administrativeStatuses');
    }


    /** @test */
    public function show_teachers_management_check_teachers_data()
    {
        initialize_tenant_roles_and_permissions();
        initialize_user_types();
        initialize_job_types();
        initialize_forces();
        initialize_families();
        initialize_specialities();
        initialize_users();
        initialize_departments();
        initialize_teachers();

        $staffManager = create(User::class);
        $this->actingAs($staffManager);
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $staffManager->assignRole($role);

        $response = $this->get('/teachers');

        $response->assertSuccessful();

        // Check required Fields for teachers component
        $response->assertViewHas('teachers',function ($teachers) {
            $teacher = $teachers->first();
            return array_key_exists('id', $teacher) &&
                array_key_exists('code', $teacher) &&
                array_key_exists('formatted_created_at_diff', $teacher) &&
                array_key_exists('formatted_created_at', $teacher) &&
                array_key_exists('formatted_updated_at', $teacher) &&
                array_key_exists('formatted_updated_at_diff', $teacher) &&
                array_key_exists('hashid', $teacher) &&
                array_key_exists('name', $teacher) &&
                array_key_exists('email', $teacher) &&
                array_key_exists('fullname', $teacher) &&
                array_key_exists('department_code', $teacher) &&
                array_key_exists('department', $teacher) &&
                array_key_exists('specialty', $teacher) &&
                array_key_exists('specialty_code', $teacher) &&
                array_key_exists('family', $teacher) &&
                array_key_exists('family_code', $teacher) &&
                array_key_exists('force', $teacher) &&
                array_key_exists('administrative_status', $teacher) &&
                array_key_exists('administrative_status_code', $teacher) &&
                array_key_exists('job', $teacher) &&
                array_key_exists('job_description', $teacher) &&
                array_key_exists('job_start_at', $teacher) &&
                array_key_exists('job_end_at', $teacher) &&
                array_key_exists('job_family', $teacher) &&
                array_key_exists('job_specialty', $teacher) &&
                array_key_exists('job_specialty_code', $teacher) &&
                array_key_exists('job_order', $teacher) &&
                array_key_exists('full_search', $teacher) &&
                array_key_exists('titulacio_acces', $teacher) &&
                array_key_exists('altres_titulacions', $teacher) &&
                array_key_exists('idiomes', $teacher) &&
                array_key_exists('perfil_professional', $teacher) &&
                array_key_exists('altres_formacions', $teacher) &&
                array_key_exists('data_superacio_oposicions', $teacher) &&
                array_key_exists('lloc_destinacio_definitiva', $teacher) &&
                array_key_exists('data_inici_treball', $teacher) &&
                array_key_exists('data_incorporacio_centre', $teacher) &&
                array_key_exists('person_notes', $teacher) &&
                array_key_exists('givenName', $teacher) &&
                array_key_exists('sn1', $teacher) &&
                array_key_exists('sn2', $teacher) &&
                array_key_exists('person_notes', $teacher) &&
                array_key_exists('givenName', $teacher) &&
                array_key_exists('sn1', $teacher) &&
                array_key_exists('sn2', $teacher) &&
                array_key_exists('birthdate', $teacher) &&
                array_key_exists('birthplace', $teacher) &&
                array_key_exists('gender', $teacher) &&
                array_key_exists('phone', $teacher) &&
                array_key_exists('other_phones', $teacher) &&
                array_key_exists('mobile', $teacher) &&
                array_key_exists('other_mobiles', $teacher) &&
                array_key_exists('personal_email', $teacher) &&
                array_key_exists('other_emails', $teacher) &&
                array_key_exists('identifier', $teacher) &&
                array_key_exists('identifier_type', $teacher) &&
                array_key_exists('address_name', $teacher) &&
                array_key_exists('address_number', $teacher) &&
                array_key_exists('address_floor', $teacher) &&
                array_key_exists('address_floor_number', $teacher) &&
                array_key_exists('address_location', $teacher) &&
                array_key_exists('address_postalcode', $teacher) &&
                array_key_exists('address_province', $teacher);

        });
    }

}
