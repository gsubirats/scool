<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class TenantTestController.
 *
 * @package App\Http\Controllers
 */
class TenantTestController extends Controller
{
    public function index(Request $request, $tenant)
    {
        dd($request);
        dd($tenant);
    }

    public function index2($tenant)
    {
        dd($tenant);
    }

}
