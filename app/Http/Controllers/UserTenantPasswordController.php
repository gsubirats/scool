<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserTenantPassword;
use App\Tenant;

/**
 * Class UserTenantPasswordController.
 *
 * @package App\Http\Controllers
 */
class UserTenantPasswordController extends Controller
{
    /**
     * Change password.
     *
     * @param UpdateUserTenantPassword $request
     * @param Tenant $tenant
     * @return Tenant
     */
    public function update(UpdateUserTenantPassword $request, Tenant $tenant)
    {
        $tenant->password = bcrypt($request->password);
        $tenant->save();
        return $tenant;
    }
}
