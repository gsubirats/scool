<?php

namespace App\Http\Controllers\Tenant;

use App\Revisionable\Revision;
use Illuminate\Http\Request;
use App\Http\Resources\Tenant\Revision as RevisionResource;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function show(Request $request)
    {
        $auditLogItems = $this->auditLogs();
        return view('tenants.home',compact('auditLogItems'));
    }

    /**
     * Audit logs.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function auditLogs() {
        return collect(RevisionResource::collection(
            Revision::orderBy('created_at', 'desc')->with(['user'])->get()));
    }
}
