<?php

namespace Tests\Unit\Tenants;

use App\Models\Name;
use App\Models\Teacher;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TeacherTest.
 *
 * @package Tests\Unit
 */
class TeacherTest extends TestCase
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
        $this->assertNull(Teacher::findByCode('040'));

        $teacher = Teacher::create([
            'code' => '040',
            'user_id' => factory(User::class)->create()->id
        ]);

        $this->assertTrue($teacher->is(Teacher::findByCode('040')));

    }
}
