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
            if (!User::findByName($username)) {
                $notFree = false;
            } else {
                $username = $originalUsername . strval($i);
                $i++;
            }
        }
        return $username;
    }
}
