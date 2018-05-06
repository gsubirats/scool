<?php

namespace Tests\Feature\Tenants;

use App\Console\Kernel;
use App\Models\PendingTeacher;
use App\Models\User;
use Config;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;

/**
 * Class PendingTeachersControllerTest
 * @package Tests\Feature\Tenants
 */
class PendingTeachersControllerTest extends BaseTenantTest
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
    public function users_can_see_create_pending_teacher()
    {
        $response = $this->get('/add_teacher');
        $response->assertSuccessful();

        $response->assertViewIs('tenants.teacher.show_pending_teacher');
        $response->assertViewHas('specialties');
        $response->assertViewHas('forces');
        $response->assertViewHas('administrative_statuses');

    }

    /** @test */
    public function regular_user_cannot_see_pending_teachers()
    {
        $user = create(User::class);
        $this->actingAs($user,'api');
        $response = $this->get('/api/v1/pending_teachers');
        $response->assertStatus(403);

    }

    /** @test */
    public function teacher_manager_can_see_pending_teachers()
    {
        $this->withoutExceptionHandling();
        $teachersManager = create(User::class);
        $this->actingAs($teachersManager,'api');
        $role = Role::firstOrCreate([
            'name' => 'TeachersManager',
            'guard_name' => 'web'
        ]);
        Config::set('auth.providers.users.model', User::class);
        $teachersManager->assignRole($role);

        PendingTeacher::create([
            'name' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'identifier' => '38111210A',
            'birthdate' => '1978-02-03',
            'street' => 'Plaza Catalunya',
            'number' => '1714',
            'floor' => '4',
            'floor_number' => '2',
            'postal_code' => '43500',
            'locality' => 'TORTOSA',
            'province' => 'TARRAGONA',
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'telephone' => '689568945',
            'other_telephones' => '689568945',
            'degree' => 'Enginyer en telecomunicacions',
            'other_degrees' => 'Master Cifuentes, Master professors',
            'languages' => 'Anglès B1, Alemany B2',
            'profiles' => 'Puto crack, Director de directors',
            'other_training' => 'Puto crack, Director de directors',
            'force_id' => 1,
            'specialty_id' => 1,
            'teacher_start_date' => '2018-04-03',
            'start_date' =>  '2015-09-01',
            'opositions_date' => '2014-06-03',
            'administrative_status_id' => 1,
            'destination_place' => 'Quinta forca',
            'teacher_id' => 1
        ]);

        PendingTeacher::create([
            'name' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeans',
            'identifier' => '10235832G',
            'birthdate' => '1978-06-03',
            'street' => 'Plaza 1 Octubre',
            'number' => '1714',
            'floor' => '3',
            'floor_number' => '2',
            'postal_code' => '43500',
            'locality' => 'TORTOSA',
            'province' => 'TARRAGONA',
            'email' => 'pepaparda@jeans.com',
            'other_emails' => 'pepaparda@gmail.com,pepaparda@xtec.cat',
            'telephone' => '649568945',
            'other_telephones' => '649568945,65978452',
            'degree' => 'Enginyer en telecomunicacions',
            'other_degrees' => 'Master Cifuentes, Master professors',
            'languages' => 'Anglès B1, Alemany B2',
            'profiles' => 'Puta crack, Directora de directores',
            'other_training' => 'Puta crack, Directora de directores',
            'force_id' => 1,
            'specialty_id' => 1,
            'teacher_start_date' => '2018-04-03',
            'start_date' =>  '2015-09-01',
            'opositions_date' => '2014-06-03',
            'administrative_status_id' => 1,
            'destination_place' => 'Quinta forca i una mica més',
            'teacher_id' => 1
        ]);

        $response = $this->json('GET','/api/v1/pending_teachers');

        $response->assertSuccessful();

        $response->assertJsonStructure([[
            'name',
            'sn1',
            'sn2',
            'identifier',
            'birthdate',
            'street',
            'number',
            'floor',
            'floor_number',
            'postal_code',
            'locality',
            'province',
            'email',
            'other_emails',
            'telephone',
            'other_telephones',
            'degree',
            'other_degrees',
            'languages',
            'profiles',
            'other_training',
            'force_id',
            'specialty_id',
            'teacher_start_date',
            'start_date',
            'opositions_date',
            'administrative_status_id',
            'destination_place',
            'teacher_id'
        ]]);
    }

    /** @test */
    public function users_can_create_pending_teacher()
    {

        $this->assertCount(0,PendingTeacher::all());

        $response = $this->json('POST','api/v1/add_teacher', [
            'name' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'identifier' => '38111210A',
            'birthdate' => '1978-02-03',
            'street' => 'Plaza Catalunya',
            'number' => '1714',
            'floor' => '4',
            'floor_number' => '2',
            'postal_code' => '43500',
            'locality' => 'TORTOSA',
            'province' => 'TARRAGONA',
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'telephone' => '689568945',
            'other_telephones' => '689568945',
            'degree' => 'Enginyer en telecomunicacions',
            'other_degrees' => 'Master Cifuentes, Master professors',
            'languages' => 'Anglès B1, Alemany B2',
            'profiles' => 'Puto crack, Director de directors',
            'other_training' => 'Puto crack, Director de directors',
            'force_id' => 1,
            'specialty_id' => 1,
            'teacher_start_date' => '2018-04-03',
            'start_date' =>  '2015-09-01',
            'opositions_date' => '2014-06-03',
            'administrative_status_id' => 1,
            'destination_place' => 'Quinta forca',
            'teacher_id' => 1,

        ]);

        $response->assertSuccessful();

        $response->assertJsonStructure([
            'name',
            'sn1',
            'sn2',
            'identifier',
            'birthdate',
            'street',
            'number',
            'floor',
            'floor_number',
            'postal_code',
            'locality',
            'province',
            'email',
            'other_emails',
            'telephone',
            'other_telephones',
            'degree',
            'other_degrees',
            'languages',
            'profiles',
            'other_training',
            'force_id',
            'specialty_id',
            'teacher_start_date',
            'start_date',
            'opositions_date',
            'administrative_status_id',
            'destination_place',
            'teacher_id'
        ]);

        $response->assertJson([
            'name' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'identifier' => '38111210A',
            'birthdate' => '1978-02-03',
            'street' => 'Plaza Catalunya',
            'number' => '1714',
            'floor' => '4',
            'floor_number' => '2',
            'postal_code' => '43500',
            'locality' => 'TORTOSA',
            'province' => 'TARRAGONA',
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'telephone' => '689568945',
            'other_telephones' => '689568945',
            'degree' => 'Enginyer en telecomunicacions',
            'other_degrees' => 'Master Cifuentes, Master professors',
            'languages' => 'Anglès B1, Alemany B2',
            'profiles' => 'Puto crack, Director de directors',
            'other_training' => 'Puto crack, Director de directors',
            'force_id' => 1,
            'specialty_id' => 1,
            'teacher_start_date' => '2018-04-03',
            'start_date' =>  '2015-09-01',
            'opositions_date' => '2014-06-03',
            'administrative_status_id' => 1,
            'destination_place' => 'Quinta forca',
            'teacher_id' => 1
        ]);

        $this->assertCount(1, PendingTeacher::all());
    }

    /** @test */
    public function users_cannot_create_pending_teacher_without_valid_data()
    {
        $response = $this->json('POST','api/v1/add_teacher');

        $response->assertStatus(422);
    }
}
