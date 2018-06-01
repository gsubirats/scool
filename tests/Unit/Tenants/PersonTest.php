<?php

namespace Tests\Unit\Tenants;

use App\Models\Person;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class PersonTest.
 *
 * @package Tests\Unit
 */
class PersonTest extends TestCase
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
    public function fullname_accessor()
    {
        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);

        $this->assertEquals($person->fullname,'Pardo Jeans, Pepe');

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
        ]);

        $this->assertEquals($person->fullname,'Pardo, Pepe');
    }

    /** @test */
    public function name_accessor()
    {
        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);

        $this->assertEquals($person->name,'Pepe Pardo Jeans');

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo'
        ]);

        $this->assertEquals($person->name,'Pepe Pardo');
    }
}
