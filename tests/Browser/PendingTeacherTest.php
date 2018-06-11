<?php

namespace Tests\Browser;

use App\Models\PendingTeacher;
use App\Models\User;
use Config;
use Illuminate\Contracts\Console\Kernel;
use Spatie\Permission\Models\Role;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

/**
 * Class PendingTeacherTest.
 *
 * @package Tests\Browser
 */
class PendingTeacherTest extends DuskTestCase
{

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
    public function pending_teachers_test()
    {
        apply_tenant('iesebre');
//
        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('https://iesebre.scool.test/nou_professor')
                ->type('name', 'Pepe')
                ->type('sn1', 'Pardo')
                ->type('sn2', 'Jeans')
                ->type('identifier', '39388406D')
                ->type('formattedBirthdate', '02/03/1988')
                ->type('street', 'C/ Beseit')
                ->type('number', '24')
                ->type('floor', '1r')
                ->type('floor_number', '2a')
                ->type('postal_code', '43500')
                ->keys('input[name="postal_code"]', '{tab}')
                ->type('locality', 'TORTOSA')
                ->keys('input[name="locality"]', '{tab}')
                ->type('province', 'Tarragona')
                ->keys('input[name="province"]', '{tab}')
                ->type('emailfield', 'myemail@jeans.com')
                ->type('other_emails', 'myemail@gmail.com,myemail@xtec.cat')
                ->keys('input[name="other_emails"]', '{tab}')
                ->type('mobile', '679845759')
                ->type('other_mobiles', '645845759,689758841')
                ->keys('input[name="other_mobiles"]', '{tab}')
                ->type('phone', '977405859')
                ->type('other_phones', '977405889,977456889')
                ->keys('input[name="other_phones"]', '{tab}')
                ->type('degree', 'Enginyeria en Telecomunicacions')
                ->type('other_degrees', 'Master en bla bla bla')
                ->type('languages', 'Anglès')
                ->type('profiles', 'Perfil TIC, CLIC, LIL, lOl')
                ->type('other_training', 'Fuster')
                ->type('specialty', 'Informàtica')
                ->keys('input[name="specialty"]', '{tab}')
                ->type('force', 'Mestres')
                ->keys('input[name="force"]', '{tab}')
                ->type('teacher_start_date', '2009')
                ->type('formatted_start_date', '01/09/2010')
                ->type('opositions_date', 'Juny 2007')
                ->type('administrative_status', 'Funcionari/a amb plaça definitiva')
                ->keys('input[name="administrative_status"]', '{tab}')
                ->type('destination_place', 'Quinto Pino')
                ->type('teacher', 'Dolors Sanjuan Aubà')
                ->keys('input[name="teacher"]', '{tab}');
            $browser->driver->executeScript('window.scrollTo(0, document.body.scrollHeight);');
            $browser->click('#sendButton')->pause(500)->assertSee('Dades enviades correctament');
        });
        $pendingTeacher = PendingTeacher::orderBy('created_at', 'desc')->first();

        $this->assertEquals('Pepe',$pendingTeacher->name);
        $this->assertEquals('Pardo',$pendingTeacher->sn1);
        $this->assertEquals('Jeans',$pendingTeacher->sn2);
        $this->assertEquals('39388406D',$pendingTeacher->identifier);
        $this->assertEquals('DNI/NIF',$pendingTeacher->identifier_type);
        $this->assertEquals('1988-03-02',$pendingTeacher->birthdate);
        $this->assertEquals('C/ Beseit',$pendingTeacher->street);
        $this->assertEquals('24',$pendingTeacher->number);
        $this->assertEquals('1r',$pendingTeacher->floor);
        $this->assertEquals('2a',$pendingTeacher->floor_number);
        $this->assertEquals('TORTOSA',$pendingTeacher->locality);
        $this->assertEquals('35130',$pendingTeacher->locality_id);
        $this->assertEquals('Tarragona',$pendingTeacher->province);
        $this->assertEquals('36',$pendingTeacher->province_id);
        $this->assertEquals('myemail@jeans.com',$pendingTeacher->email);
        $this->assertEquals('myemail@gmail.com,myemail@xtec.cat',$pendingTeacher->other_emails);
        $this->assertEquals('977405859',$pendingTeacher->phone);
        $this->assertEquals('679845759',$pendingTeacher->mobile);
        $this->assertEquals('645845759,689758841',$pendingTeacher->other_mobiles);
        $this->assertEquals('Enginyeria en Telecomunicacions',$pendingTeacher->degree);
        $this->assertEquals('Master en bla bla bla',$pendingTeacher->other_degrees);
        $this->assertEquals('Anglès',$pendingTeacher->languages);
        $this->assertEquals('Perfil TIC, CLIC, LIL, lOl',$pendingTeacher->profiles);
        $this->assertEquals('Fuster',$pendingTeacher->other_training);
        $this->assertEquals(null,$pendingTeacher->photo);
        $this->assertEquals(null,$pendingTeacher->identifier_photocopy);
        $this->assertEquals(1,$pendingTeacher->force_id);
        $this->assertEquals(11,$pendingTeacher->specialty_id);
        $this->assertEquals(2009,$pendingTeacher->teacher_start_date);
        $this->assertEquals('2010-09-01',$pendingTeacher->start_date);
        $this->assertEquals('Juny 2007',$pendingTeacher->opositions_date);
        $this->assertEquals(1,$pendingTeacher->administrative_status_id);
        $this->assertEquals('Quinto Pino',$pendingTeacher->destination_place);
        $this->assertEquals(1,$pendingTeacher->teacher_id);

//
        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('https://iesebre.scool.test/login')
                ->type('email', 'sergiturbadenas@gmail.com')
                ->type('#login_password', '123456')
                ->click('#login_button')
                ->pause(1000);
        });

        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('https://iesebre.scool.test/teachers')
                ->click('#pending_teacher_remove_myemail_jeans_com')
                ->pause(500000)
                ->click('#confirm_button')
            ->pause(50000);
        });

        $this->browse(function (Browser $browser) {
            $browser->maximize();
            $browser->visit('https://iesebre.scool.test/teachers')
                ->pause(20000)
                ->assertSee('507')
                ->assertSee('myemail@jeans.com')
                ->assertSee('Pardo Jeans, Pepe')
                ->assertSee('679845759')
                ->click('#pending_teacher_see_myemail_jeans_com')
                ->pause(2000)
                ->assertSee('Pepe')
                ->assertSee('Pardo')
                ->assertSee('Jeans')
                ->assertSee('DNI/NIF')
                ->assertSee('39388406D')
                ->assertSee('02/03/1988')
                ->assertSee('C/ Beseit')
                ->assertSee('24')
                ->assertSee('1r')
                ->assertSee('2a')
                ->assertSee('TORTOSA')
                ->assertSee('Tarragona')
                ->assertSee('myemail@jeans.com')
                ->assertSee('myemail@gmail.com,myemail@xtec.cat')
                ->assertSee('679845759')
                ->assertSee('645845759,689758841')
                ->assertSee('977405859')
                ->assertSee('977405889,977456889')
                ->assertSee('Enginyeria en Telecomunicacions')
                ->assertSee('Master en bla bla bla')
                ->assertSee('Anglès')
                ->assertSee('Perfil TIC, CLIC, LIL, lOl')
                ->assertSee('Fuster')
                ->assertSee('Informàtica')
                ->assertSee("Professors d'ensenyament secundari")
                ->assertSee('2009')
                ->assertSee('01/09/2010')
                ->assertSee('Juny 2007')
                ->assertSee('Funcionari/a amb plaça definitiva')
                ->assertSee('Quinto Pino')
                ->assertSee('Dolors Sanjuan Aubà')
                ->assertSee('myemail')
                ->assertSee('pepepardo');
        });
    }
}
