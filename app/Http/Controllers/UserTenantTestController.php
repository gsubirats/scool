<?php

namespace App\Http\Controllers;

use App\Tenant;
use Illuminate\Http\Request;

/**
 * Class UserTenantTestController.
 * 
 * @package App\Http\Controllers
 */
class UserTenantTestController extends Controller
{
    /**
     * @param Request $request
     * @param $tenant
     * @return mixed
     */
    public function index(Request $request, $tenant)
    {
        $tenant = $request->user()->tenants()->findOrFail($tenant);

        return $tenant->test();
    }
}
