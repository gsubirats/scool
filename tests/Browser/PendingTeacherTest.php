<?php

namespace Tests\Browser;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://iesebre.scool.test/nou_professor')
                    ->type('name', 'Pepe')
                    ->type('sn1', 'Pardo')
                    ->type('sn2', 'Jeans')
//                    ->type('identifierType', 'NIE')
                    ->type('name', 'Pepe')
                    ->type('identifier', '39388406D')
                    ->type('formattedBirthdate','02/03/1988')
                    ->type('street','C/ Beseit')
                    ->type('number','24')
                    ->type('floor','1r')
                    ->type('floor_number','2a')
                    ->type('postal_code','43500')
                    ->keys('input[name="postal_code"]', '{tab}')
                    ->type('locality','TORTOSA')
                    ->keys('input[name="locality"]', '{tab}')
                    ->type('province','Tarragona')
                    ->keys('input[name="province"]', '{tab}')
                    ->type('emailfield','pepepardo@jeans.com')
                    ->type('other_emails','pepepardo@gmail.com,pepepardo@xtec.cat')
                    ->type('mobile','679845759')
                    ->type('other_mobiles','645845759,689758841')
                    ->type('phone','977405859')
                    ->type('other_phones','977405889,977456889')
                    ->type('degree','Enginyeria en Telecomunicacions')
                    ->type('other_degrees','Master en bla bla bla')
                    ->type('languages','Anglès')
                    ->type('profiles','Perfil TIC, CLIC, LIL, lOl')
                    ->type('other_training','Fuster')
                    ->type('specialty','Informàtica')
                    ->keys('input[name="specialty"]', '{tab}')
                    ->type('force','Mestres')
                    ->keys('input[name="force"]', '{tab}')
                    ->type('teacher_start_date','2009')
                    ->type('formatted_start_date','01/09/2010')
                    ->type('opositions_date','Juny 2007')
                    ->type('administrative_status','Funcionari/a amb plaça definitiva')
                    ->keys('input[name="administrative_status"]', '{tab}')
                    ->type('destination_place','Quinto Pino')
                    ->type('teacher','Dolors Sanjuan Aubà')
                    ->keys('input[name="teacher"]', '{tab}');
            $browser->driver->executeScript('window.scrollTo(0, document.body.scrollHeight);');
            $browser->click('#sendButton')->pause(500000);
        });
    }
}
