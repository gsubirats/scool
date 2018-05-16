<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Mockery\Exception;
use PulkitJalan\Google\Client;
use PulkitJalan\Google\Facades\Google;

/**
 * Class GoogleSuiteTestConnectionController.
 *
 * @package App\Http\Controllers
 */
class GoogleSuiteTestConnectionController extends Controller
{
    /**
     * GoogleSuiteTestConnectionController constructor.
     */
    public function __construct()
    {
        tune_google_client();
    }

    /**
     * Index.
     *
     * @param Request $request
     * @return array|\Exception|Exception
     */
    public function index(Request $request)
    {
        $directory = Google::make('directory');
        $email = config('google.admin_email');

        try {
            $r = $directory->users->get($email);
        } catch (Exception $e) {
            return $e;
        }

        if ($r->emails[0]['address'] === $email && $r->emails[0]['primary']) {
            return ['result' => 'Ok'];
        } else {
            return [
                'result' => 'Error',
                'message' => 'User ' . $email . ' not found!'
            ];
        }
    }
}
