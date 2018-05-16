<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use PulkitJalan\Google\Client;
use PulkitJalan\Google\Facades\Google;

/**
 * Class GoogleSuiteTestConnectionController.
 *
 * @package App\Http\Controllers
 */
class GoogleSuiteTestConnectionController extends Controller
{
    public function index()
    {
        //        // Override pulkitjalan/google-apiclient
        app()->extend(\PulkitJalan\Google\Client::class, function ($command, $app) {
            $config = $app['config']['google'];
            $user = 'sergitur@iesebre.com';
            return new Client($config, $user);
        });

        $googleClient = Google::getClient();
//        dd($googleClient);
        $directory = Google::make('directory');
        $r = $directory->users->get('sergitur@iesebre.com');
        if($r) {
            dump( "Name: ".$r->name->fullName."<br/>");
            echo "Suspended?: ".(($r->suspended === true) ? 'Yes' : 'No')."<br/>";
            echo "Org/Unit/Path: ".$r->orgUnitPath."<br/>";
        } else {
            echo "User does not exist: $email<br/>";
        }
//        dump('Class: ' . get_class($directory));
//        dd($directory);
    }
}
