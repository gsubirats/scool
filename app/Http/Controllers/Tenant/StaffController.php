<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowStaffManagement;
use App\Models\Family;
use App\Models\Specialty;
use App\Models\Staff;
use App\Models\StaffType;

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
        $staffTypes = StaffType::all();
        $specialties = Specialty::all();
        $families = Family::all();
        return view('tenants.staff.show',compact(
            'staff',
            'staffTypes',
            'specialties',
            'families'
        ));
    }
}
