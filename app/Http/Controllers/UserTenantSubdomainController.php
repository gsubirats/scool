<?php

namespace App\Http\Controllers;

use App\Events\TenantUpdated;
use App\Tenant;
use Illuminate\Http\Request;

/**
 * Class UserTenantSubdomainController.
 *
 * @package App\Http\Controllers
 */
class UserTenantSubdomainController extends Controller
{
    public function update(Request $request, Tenant $tenant)
    {
        $tenant->subdomain = $request->subdomain;
        $tenant->save();
        event(new TenantUpdated($tenant));
        return $tenant;
    }
}
