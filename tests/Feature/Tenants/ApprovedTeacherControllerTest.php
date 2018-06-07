<?php

namespace Tests\Feature;

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

        $response = $this->json('POST','/api/v1/approved_teacher', [
            'username' => 'pepepardo',
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans',
            'photo' => 'tenant_test/user_photos/photo.png',
            'code' => '040',
            'user_id' => 1,
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
        ]);

        $this->assertNotNull($user = User::findByEmail('pepepardo@iesebre.com'));
        $this->assertEquals('Pepe Pardo Jeans',$user->name);
        $this->assertEquals('pepepardo@iesebre.com',$user->email);
        $this->assertEquals('tenant_test/user_photos/photo.png',$user->photo);
        $this->assertEquals('7e98f5986f80d2cbd012df6bd8801558',$user->photo_hash);

        $response->assertSuccessful();
        $this->assertNotNull($teacher = Teacher::findByCode('040'));
        $this->assertEquals(1,$teacher->user_id);
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
