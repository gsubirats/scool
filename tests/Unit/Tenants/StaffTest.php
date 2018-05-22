<?php

namespace Tests\Unit\Tenants;

use App\Console\Kernel;
use App\Models\Job;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class StaffTest.
 *
 * @package Tests\Unit
 */
class StaffTest extends TestCase
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
    function can_get_formatted_created_at_date()
    {
        $staff =Job::create([
            'type_id' => 2,
            'code' => 'CODE',
            'created_at' => Carbon::parse('2016-12-01 8:00pm'),
            'user_id'=> 1,
            'order' => 1
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $staff->formatted_created_at);
    }

    /** @test */
    function can_get_formatted_updated_at_date()
    {
        $staff =Job::create([
            'type_id' => 2,
            'code' => 'CODE',
            'updated_at' => Carbon::parse('2016-12-01 8:00pm'),
            'order' => 1,
            'user_id'=> 1
        ]);

        $this->assertEquals('08:00:00PM 01-12-2016', $staff->formatted_updated_at);
    }

    /** @test */
    function can_get_formatted_created_at_date_diff()
    {
        $staff =Job::create([
            'type_id' => 2,
            'code' => 'CODE',
            'created_at' => Carbon::now()->subMonth(),
            'user_id'=> 1,
            'order' => 1
        ]);

        $this->assertEquals('1 mes abans', $staff->formatted_created_at_diff);
    }

    /** @test */
    function can_get_formatted_updated_at_date_diff()
    {
        $staff =Job::create([
            'type_id' => 2,
            'code' => 'CODE',
            'updated_at' => Carbon::now()->subMonth(),
            'user_id'=> 1,
            'order' => 1
        ]);

        $this->assertEquals('1 mes abans', $staff->formatted_updated_at_diff);
    }
}
