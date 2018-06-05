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
        $response->assertViewHas('specialties');
        $response->assertViewHas('forces');
        $response->assertViewHas('administrativeStatuses');
    }


    /** @test */
    public function show_teachers_management_check_teachers_data()
    {
        $this->withoutExceptionHandling();
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
        $response->assertViewHas('teachers',function ($teachers) {
            return array_key_exists('id',$teachers->first()) &&
                array_key_exists('code',$teachers->first()) &&
                array_key_exists('formatted_created_at_diff',$teachers->first()) &&
                array_key_exists('formatted_created_at',$teachers->first()) &&
                array_key_exists('formatted_updated_at',$teachers->first()) &&
                array_key_exists('formatted_updated_at_diff',$teachers->first()) &&
                array_key_exists('hashid',$teachers->first()) &&
                array_key_exists('name',$teachers->first()) &&
                array_key_exists('email',$teachers->first()) &&
                array_key_exists('fullname',$teachers->first()) &&
                array_key_exists('department_code',$teachers->first()) &&
                array_key_exists('department',$teachers->first()) &&
                array_key_exists('specialty',$teachers->first()) &&
                array_key_exists('specialty_code',$teachers->first()) &&
                array_key_exists('family',$teachers->first()) &&
                array_key_exists('family_code',$teachers->first()) &&
                array_key_exists('force',$teachers->first()) &&
                array_key_exists('administrative_status',$teachers->first()) &&
                array_key_exists('administrative_status_code',$teachers->first()) &&
                array_key_exists('job',$teachers->first()) &&
                array_key_exists('job_description',$teachers->first()) &&
                array_key_exists('job_start_at',$teachers->first()) &&
                array_key_exists('job_end_at',$teachers->first()) &&
                array_key_exists('job_family',$teachers->first()) &&
                array_key_exists('job_specialty',$teachers->first()) &&
                array_key_exists('job_specialty_code',$teachers->first()) &&
                array_key_exists('job_order',$teachers->first()) &&
                array_key_exists('full_search',$teachers->first()) &&
                array_key_exists('titulacio_acces',$teachers->first()) &&
                array_key_exists('altres_titulacions',$teachers->first()) &&
                array_key_exists('idiomes',$teachers->first()) &&
                array_key_exists('perfil_professional',$teachers->first()) &&
                array_key_exists('altres_formacions',$teachers->first()) &&
                array_key_exists('data_superacio_oposicions',$teachers->first()) &&
                array_key_exists('lloc_destinacio_definitiva',$teachers->first()) &&
                array_key_exists('data_inici_treball',$teachers->first()) &&
                array_key_exists('data_incorporacio_centre',$teachers->first()) &&
                array_key_exists('person_notes',$teachers->first()) &&
                array_key_exists('givenName',$teachers->first()) &&
                array_key_exists('sn1',$teachers->first()) &&
                array_key_exists('sn2',$teachers->first()) &&
                array_key_exists('person_notes',$teachers->first()) &&
                array_key_exists('givenName',$teachers->first()) &&
                array_key_exists('sn1',$teachers->first()) &&
                array_key_exists('sn2',$teachers->first()) &&
                array_key_exists('birthdate',$teachers->first()) &&
                array_key_exists('birthplace',$teachers->first()) &&
                array_key_exists('gender',$teachers->first()) &&
                array_key_exists('phone',$teachers->first()) &&
                array_key_exists('other_phones',$teachers->first()) &&
                array_key_exists('mobile',$teachers->first()) &&
                array_key_exists('other_mobiles',$teachers->first()) &&
                array_key_exists('personal_email',$teachers->first()) &&
                array_key_exists('other_emails',$teachers->first()) &&
                array_key_exists('identifier',$teachers->first()) &&
                array_key_exists('identifier_type',$teachers->first()) &&
                array_key_exists('address_name',$teachers->first()) &&
                array_key_exists('address_number',$teachers->first()) &&
                array_key_exists('address_floor',$teachers->first()) &&
                array_key_exists('address_floor_number',$teachers->first()) &&
                array_key_exists('address_location',$teachers->first()) &&
                array_key_exists('address_postalcode',$teachers->first()) &&
                array_key_exists('address_province',$teachers->first());

        });
    }

}
