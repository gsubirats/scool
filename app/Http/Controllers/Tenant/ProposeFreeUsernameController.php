<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class ProposeFreeUsernameController.
 *
 * @package App\Http\Controllers
 */
class ProposeFreeUsernameController extends Controller
{
    /**
     * Index.
     *
     * @param $tenant
     * @param $name
     * @param $sn1
     * @return string
     */
    public function index($tenant, $name, $sn1)
    {
        return $this->proposeUsername($name,$sn1);
    }

    private function nospaces($string) {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * Propose username.
     *
     * @param $name
     * @param $sn1
     * @return string
     */
    private function proposeUsername($name, $sn1)
    {
        $originalUsername = mb_strimwidth(trim(str_slug($this->nospaces($name))),0,10,'') .
            mb_strimwidth(trim(str_slug($this->nospaces($sn1))),0,10,'');
        $username = $originalUsername;
        $notFree = true;
        $i = 1;
        while ($notFree) {
            // TODO email domain
            if (!User::findByEmail($username . '@iesebre.com')) {
                $notFree = false;
            } else {
                $username = $originalUsername . strval($i);
                $i++;
            }
        }
        return $username;
    }
}
