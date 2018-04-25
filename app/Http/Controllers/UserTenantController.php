<?php

namespace App\Http\Controllers;

use App\Events\TenantCreated;
use App\Tenant;
use Illuminate\Http\Request;

/**
 * Class UserTenantController.
 *
 * @package App\Http\Controllers
 */
class UserTenantController extends Controller
{
    /**
     * @param Request $request
     * @param $tenant
     */
    public function index(Request $request)
    {
        return $request->user()->tenants;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO validate request.

        $request->user()->addTenant($tenant = Tenant::create([
            'name' => $request->name,
            'subdomain' => $request->subdomain,
            'hostname' => 'localhost',
            'database' => $request->subdomain,
            'username' => $request->subdomain,
            'password' => str_random(),
            'port' => 3306
        ]));

        event(new TenantCreated($tenant,$request->password));
    }

}

