<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowStaffManagement;
use App\Tenant\Models\Staff;

/**
 * Class StaffController.
 *
 * @package App\Http\Controllers\Tenant
 */
class StaffController extends Controller
{
    /**
     * Show staff management.
     *
     * @param ShowStaffManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowStaffManagement $request)
    {
        $staff = Staff::all();
        return view('tenants.staff.show',compact('staff'));
    }
}
