<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\AdministrativeStatus;
use App\Models\Employee;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Job;
use App\Models\Location;
use App\Models\Person;
use App\Models\Teacher;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\BaseTenantTest;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ApprovedTeacherControllerTest.
 *
 * @package Tests\Feature
 */
class ApprovedTeacherControllerTest extends BaseTenantTest
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
    public function store_approved_teacher()
    {
        $this->withoutExceptionHandling();
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        IdentifierType::create([
            'name' => 'NIF'
        ]);

        AdministrativeStatus::create([
            'name' => 'Substitut/a',
            'code' => 'SUBSTITUT'
        ]);

        $job = Job::create([
            'code' => '018',
            'type_id' => 1,
            'specialty_id' => 1,
            'family_id' => 1,
            'order' => 1,
        ]);

        $role = Role::firstOrCreate(['name' => 'Teacher','guard_name' => 'web']);
        $teacher_code = Teacher::firstAvailableCode();
        $response = $this->json('POST','/api/v1/approved_teacher', [
            'username' => 'pepepardo',
            'name' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'photo' => 'tenant_test/user_photos/photo.png',

            //Job
            'job_id' => $job->id,

            //Teacher
            'code' => '040',
            'administrative_status_id' => 1,
            'specialty_id' => 1,
            'titulacio_acces' => 'Enginyer en Enginyeria',
            'altres_titulacions' => 'Doctorat en Filologia Hispànica',
            'idiomes' => 'Anglès',
            'altres_formacions' => 'Nivell D Català',
            'perfil_professional' => 'Digital, Clil, Projectes FP',
            'data_inici_treball' => '2004',
            'data_incorporacio_centre' => '1993-09-01',
            'data_superacio_oposicions' => '2009',
            'lloc_destinacio_definitiva' => 'Tarragona',

            //Person
            'identifier' => '24196166M',
            'identifier_type' => 'NIF',
            'birthdate' => '2008-05-25',
            'birthplace_id' => 1,
            'gender' => 'Home',
            'civil_status' => 'Casat/da',
            'phone' => '977405689',
            'other_phones' => '977405675,977405678',
            'mobile' => '679585427',
            'other_mobiles' => '689585457,679582424',
            'email' => 'pepepardo@jeans.com',
            'other_emails' => 'pepepardo@gmail.com,pepepardo@xtec.cat',
            'notes' => 'Bla bla bla',

            //Address

            'street' => 'C/ Beseit',
            'number' => '24',
            'floor' => '1r',
            'floor_number' => '2a',
            'locality' => 'Roquetes',
            'province_id' => 1,
            'postal_code' => '43520'

        ]);
        $response->assertSuccessful();

        //Check user
        $this->assertNotNull($user = User::findByEmail('pepepardo@iesebre.com'));
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('pepepardo@iesebre.com',$user->email);
        $this->assertEquals('tenant_test/user_photos/photo.png',$user->photo);
        $this->assertEquals('7e98f5986f80d2cbd012df6bd8801558',$user->photo_hash);

        // Check teacher rol
        $this->assertTrue($user->hasRole('Teacher'));

        // Check employee (a job is assigned to user)
        $this->assertNotNull($employee = Employee::where('user_id',$user->id)->where('job_id', $job->id)->first());
        $this->assertTrue($employee->start_at->diffInMinutes() === 0);
        $this->assertEquals(null,$employee->end_at);

        //Check teacher
        $this->assertNotNull($teacher = Teacher::findByCode($teacher_code));
        $this->assertEquals($user->id,$teacher->user_id);
        $this->assertEquals(1,$teacher->administrative_status_id);
        $this->assertEquals(1,$teacher->specialty_id);
        $this->assertEquals('Enginyer en Enginyeria',$teacher->titulacio_acces);
        $this->assertEquals('Doctorat en Filologia Hispànica',$teacher->altres_titulacions);
        $this->assertEquals('Anglès',$teacher->idiomes);
        $this->assertEquals('Nivell D Català',$teacher->altres_formacions);
        $this->assertEquals('Digital, Clil, Projectes FP',$teacher->perfil_professional);
        $this->assertEquals('2004',$teacher->data_inici_treball);
        $this->assertEquals('1993-09-01',$teacher->data_incorporacio_centre);
        $this->assertEquals('2009',$teacher->data_superacio_oposicions);
        $this->assertEquals('Tarragona',$teacher->lloc_destinacio_definitiva);

        //Check person
        $this->assertNotNull($person = Person::where('user_id',$user->id)->first());
        $this->assertEquals($user->id, $person->user_id);
        $this->assertEquals(1,$person->identifier_id);
        $this->assertEquals('Pepe',$person->givenName);
        $this->assertEquals('Pardo',$person->sn1);
        $this->assertEquals('Jeans',$person->sn2);
        $this->assertEquals('2008-05-25',$person->birthdate);
        $this->assertEquals('1',$person->birthplace_id);
        $this->assertEquals('Home',$person->gender);
        $this->assertEquals('Casat/da',$person->civil_status);
        $this->assertEquals('977405689',$person->phone);
        $this->assertEquals('["977405675","977405678"]',$person->other_phones);
        $this->assertEquals('679585427',$person->mobile);
        $this->assertEquals('["689585457","679582424"]',$person->other_mobiles);
        $this->assertEquals('pepepardo@jeans.com',$person->email);
        $this->assertEquals('["pepepardo@gmail.com","pepepardo@xtec.cat"]',$person->other_emails);
        $this->assertEquals('Bla bla bla',$person->notes);

        //Check identifier
        $this->assertNotNull($identifier = Identifier::where('value','24196166M')->first());
        $this->assertEquals(1,$identifier->type_id);
        $this->assertEquals('24196166M',$identifier->value);

        //Check address
        $this->assertNotNull($address = Address::where('person_id',$person->id)->first());
        $this->assertEquals($person->id,$address->person_id);
        $this->assertEquals('C/ Beseit',$address->name);
        $this->assertEquals('24',$address->number);
        $this->assertEquals('1r',$address->floor);
        $this->assertEquals('2a',$address->floor_number);
        $this->assertEquals('1',$address->location_id);
        $this->assertEquals('1',$address->province_id);

        //Check Location
        $this->assertNotNull($location = Location::find($address->location_id)->first());
        $this->assertEquals('ROQUETES',$location->name);
        $this->assertEquals('43520',$location->postalcode);

        // Cheac another teacher using an existing Location

        Location::create([
            'name' => 'TORTOSA',
            'postalcode' => '43500'
        ]);

        $response = $this->json('POST','/api/v1/approved_teacher', [
            'username' => 'pepaparda',
            'name' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeans',
            'photo' => 'tenant_test/user_photos/photo.png',

            'code' => '041',
            'administrative_status_id' => 1,
            'specialty_id' => 1,
            'titulacio_acces' => 'Enginyer en Enginyeria',
            'altres_titulacions' => 'Doctorat en Filologia Hispànica',
            'idiomes' => 'Anglès',
            'altres_formacions' => 'Nivell D Català',
            'perfil_professional' => 'Digital, Clil, Projectes FP',
            'data_inici_treball' => '2004',
            'data_incorporacio_centre' => '1993-09-01',
            'data_superacio_oposicions' => '2009',
            'lloc_destinacio_definitiva' => 'Tarragona',

            'identifier' => '72024157W',
            'identifier_type' => 'NIF',
            'birthdate' => '2008-05-25',
            'birthplace_id' => 1,
            'gender' => 'Home',
            'civil_status' => 'Casat/da',
            'phone' => '977405689',
            'other_phones' => '977405675,977405678',
            'mobile' => '679585427',
            'other_mobiles' => '689585457,679582424',
            'email' => 'pepaparda@jeans.com',
            'other_emails' => 'pepaparda@gmail.com,pepaparda@xtec.cat',
            'notes' => 'Bla bla bla',

            //Address

            'street' => 'C/ Beseit',
            'number' => '24',
            'floor' => '1r',
            'floor_number' => '2a',
            'locality' => 'TORTOSA',
            'province_id' => 1,
            'postal_code' => '43500',

            'job_id' => 1

        ]);

        $response->assertSuccessful();

        //Check Location
        $user2 = User::findByEmail('pepaparda@iesebre.com');
        $person2 = Person::where('user_id',$user2->id)->first();
        $address2 = Address::where('person_id',$person2->id)->first();
        $this->assertNotNull($location2 = Location::find($address2->location_id));
        $this->assertEquals('TORTOSA',$location2->name);
        $this->assertEquals('43500',$location2->postalcode);

    }

    /** @test */
    public function store_approved_teacher_validation()
    {
        $manager = factory(User::class)->create();
        $role = Role::firstOrCreate(['name' => 'TeachersManager']);
        Config::set('auth.providers.users.model', User::class);
        $manager->assignRole($role);
        $this->actingAs($manager,'api');

        $response = $this->json('POST','/api/v1/approved_teacher', []);

        $response->assertStatus(422);
        $response = json_decode($response->getContent());
        $this->assertEquals('The given data was invalid.', $response->message);

    }

    /** @test */
    public function regular_user_cannot_store_approved_teacher()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user,'api');

        $response = $this->json('POST','/api/v1/approved_teacher');
        $response->assertStatus(403);
    }
}
