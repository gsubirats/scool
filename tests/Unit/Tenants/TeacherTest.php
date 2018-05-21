<?php

namespace Tests\Unit\Tenants;

use App\Models\Family;
use App\Models\Force;
use App\Models\Identifier;
use App\Models\IdentifierType;
use App\Models\Location;
use App\Models\Name;
use App\Models\Specialty;
use App\Models\Staff;
use App\Models\StaffType;
use App\Models\Teacher;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\UploadedFile;
use Spatie\Permission\Models\Role;
use Storage;
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
