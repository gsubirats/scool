<?php

namespace Tests\Unit;

use App\Models\Teacher;
use App\Models\User;
use App\Repositories\TeacherRepository;
use Config;
use Illuminate\Contracts\Console\Kernel;
use stdClass;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherRepositoryTest.
 *
 * @package Tests\Unit
 */
class TeacherRepositoryTest extends TestCase
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
    public function store_teacher()
    {
        $teacherRepository = new TeacherRepository();

        $teacher = new Stdclass();
        $teacher->code = '040';
        $teacherEloquent = $teacherRepository->store($teacher);
        $this->assertInstanceOf(Teacher::class, $teacherEloquent);
        $this->assertEquals('040', $teacherEloquent->code);
        $this->assertNull($teacherEloquent->user_id);

        $teacher = new Stdclass();
        $teacher->code = '041';
        $teacher->user_id = 1;
        $teacherEloquent = $teacherRepository->store($teacher);
        $this->assertInstanceOf(Teacher::class, $teacherEloquent);
        $this->assertEquals('041', $teacherEloquent->code);
        $this->assertEquals(1, $teacherEloquent->user_id);

        $teacher = new Stdclass();
        $teacher->code = '042';
        $teacher->user_id = 2;
        $teacher->administrative_status_id =1;
        $teacher->specialty_id = 1;
        $teacher->titulacio_acces = 'Enginyer en Enginyeria';
        $teacher->altres_titulacions = 'Doctorat en Filologia Hispànica';
        $teacher->idiomes = 'Anglès';
        $teacher->altres_formacions = 'Nivell D Català';
        $teacher->perfil_professional = 'Digital, Clil, Projectes FP';
        $teacher->data_inici_treball = '2004';
        $teacher->data_incorporacio_centre = '1993-09-01';
        $teacher->data_superacio_oposicions = '2009';
        $teacher->lloc_destinacio_definitiva = 'Tarragona';
        $teacherEloquent = $teacherRepository->store($teacher);
        $this->assertInstanceOf(Teacher::class, $teacherEloquent);
        $this->assertEquals('042', $teacherEloquent->code);
        $this->assertEquals(2, $teacherEloquent->user_id);
        $this->assertEquals(1, $teacherEloquent->administrative_status_id);
        $this->assertEquals(1, $teacherEloquent->specialty_id);
        $this->assertEquals('Enginyer en Enginyeria', $teacherEloquent->titulacio_acces);
        $this->assertEquals('Doctorat en Filologia Hispànica', $teacherEloquent->altres_titulacions);
        $this->assertEquals('Anglès', $teacherEloquent->idiomes);
        $this->assertEquals('Nivell D Català', $teacherEloquent->altres_formacions);
        $this->assertEquals('Digital, Clil, Projectes FP', $teacherEloquent->perfil_professional);
        $this->assertEquals('2004', $teacherEloquent->data_inici_treball);
        $this->assertEquals('1993-09-01', $teacherEloquent->data_incorporacio_centre);
        $this->assertEquals('2009', $teacherEloquent->data_superacio_oposicions);
        $this->assertEquals('Tarragona', $teacherEloquent->lloc_destinacio_definitiva);
    }
}
