<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowStaffManagement;
use App\Models\Family;
use App\Models\Specialty;
use App\Models\Staff;
use App\Models\StaffType;
use App\Models\User;

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
        $staff = Staff::with('type','family','specialty','user')->get();
        $staffTypes = StaffType::all();
        $specialties = Specialty::with('staff','staff.family')->get();
        $families = Family::with('staff','staff.specialty')->get();
        $users = User::all();
        return view('tenants.staff.show',compact(
            'staff',
            'staffTypes',
            'specialties',
            'families',
            'users'
        ));
    }
}
