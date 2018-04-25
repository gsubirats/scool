<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class UserTenantTestAdminUserController
 * @package App\Http\Controllers
 */
class UserTenantTestAdminUserController extends Controller
{
    /**
     * @param Request $request
     * @param $tenant
     * @return mixed
     */
    public function index(Request $request, $tenant)
    {
        $tenant = $request->user()->tenants()->findOrFail($tenant);

        return $tenant->testAdminUser($request->password);
    }
}
