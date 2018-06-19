<?php

namespace Tests\Unit\Tenants;


use App\Models\User;
use App\Models\Subject;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class SubjectTest.
 *
 * @package Tests\Unit
 */
class SubjectTest extends TestCase
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
    public function find_by_code()
    {
        $this->assertNull(Subject::findByCode('040'));

        $mp_start_date = '2017-09-15';
        $mp_end_date = '2018-06-01';
        $subject = Subject::create([
            'name' => 'Disseny i implementació d’interfícies',
            'shortname'=> 'Disseny i implementació d’interfícies',
            'code' =>  'DAM_MP7_UF1',
            'number' => 1,
            'group_id' => 1,
            'study_id' => 1,
            'course_id' => 1,
            'type_id' => 1,
            'hours' => 79,
            'start_date' => $mp_start_date,
            'end_date' => $mp_end_date
        ]);

        $this->assertTrue($subject->is(Subject::findByCode('DAM_MP7_UF1')));
    }
}
