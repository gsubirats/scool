<?php

namespace Tests\Unit\Tenants;

use App\Models\Identifier;
use App\Models\IdentifierType;
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

    /** @test */
    public function find_by_identifier()
    {
        $dni = IdentifierType::create([
            'name' => 'NIF'
        ]);
        $nie = IdentifierType::create([
            'name' => 'NIE'
        ]);

        $this->assertNull(Person::findByIdentifier('50496311H'));
        $this->assertNull(Person::findByIdentifier('Z2514326V','NIE'));

        $person = Person::create([
            'givenName' => 'Pepe',
            'sn1' => 'Pardo',
            'sn2' => 'Jeans'
        ]);
        $person->identifier()->associate(Identifier::create([
            'value' => '50496311H',
            'type_id' => $dni->id
        ]));
        $person->save();

        $person2 = Person::create([
            'givenName' => 'Pepa',
            'sn1' => 'Parda',
            'sn2' => 'Jeans'
        ]);
        $person2->identifier()->associate(Identifier::create([
            'value' => 'Z2514326V',
            'type_id' => $nie->id
        ]));
        $person2->save();

        $this->assertTrue($person->is(Person::findByIdentifier('50496311H')));
        $this->assertTrue($person2->is(Person::findByIdentifier('Z2514326V','NIE')));
        $this->assertTrue($person2->is(Person::findByIdentifier('Z2514326V',2)));
        $this->assertTrue($person2->is(Person::findByIdentifier('Z2514326V', $nie)));
        $this->assertNull(Person::findByIdentifier('Z2514326V'));
    }
}
