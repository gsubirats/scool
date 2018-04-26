<?php

namespace App\Http\Controllers;

use App\Tenant;
use Illuminate\Http\Request;

/**
 * Class UserTenantNameController.
 *
 * @package App\Http\Controllers
 */
class UserTenantNameController extends Controller
{
    public function update(Request $request, Tenant $tenant)
    {
        $tenant->name = $request->name;
        $tenant->save();
        return $tenant;
    }
}
