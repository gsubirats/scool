<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowGoogleSuiteUser;
use App\Http\Requests\StoreGoogleSuiteUser;
use Google_Service_Directory_User;
use Google_Service_Directory_UserName;
use PulkitJalan\Google\Facades\Google;

/**
 * Class GoogleSuiteUsersController.
 *
 * @package App\Http\Controllers
 */
class GoogleSuiteUsersController extends Controller
{
    /**
     * GoogleSuiteTestConnectionController constructor.
     */
    public function __construct()
    {
        tune_google_client();
    }

    /**
     * Show.
     *
     * @param ShowGoogleSuiteUser $request
     * @param $tenant
     * @param $email
     * @return array|\Exception|string
     */
    public function show(ShowGoogleSuiteUser $request, $tenant, $email)
    {
        $directory = Google::make('directory');

        try {
            $r = $directory->users->get($email);
        } catch (\Exception $e) {
            return $e;
        }

        if ($r->emails[0]['address'] === $email && $r->emails[0]['primary']) {
            return json_encode($r);
        } else {
            return [
                'result' => 'Error',
                'message' => 'User ' . $email . ' not found!'
            ];
        }
    }

//https://github.com/google/google-api-php-client-services/blob/master/src/Google/Service/Directory/Resource/Users.php

    public function store(StoreGoogleSuiteUser $request)
    {

        $user = new Google_Service_Directory_User();
        $name = new Google_Service_Directory_UserName();
        $new_person = array();
// SET THE ATTRIBUTES
        $name->setGivenName('Tester1');
        $name->setFamilyName('Testerton1');
        $user->setName($name);
        $user->setHashFunction("crypt");
        $user->setPrimaryEmail("testy1.testerton@iesebre.com");
        $user->setPassword('$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'); //SECRET
// the JSON object shows us that externalIds is an array, so that's how we set it here
        $user->setExternalIds(array("value"=>28790,"type"=>"custom","customType"=>"EmployeeID"));

        $directory = Google::make('directory');
        try {
            $result = $directory->users->insert($user);
        } catch (\Exception $e) {
            dump('ERROR');
            dd($e);
            return $e;
        }

        return $result;
    }
}
