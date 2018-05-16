<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteStaff;
use App\Http\Requests\ShowStaffManagement;
use App\Http\Requests\StoreStaff;
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

    /**
     * Store.
     *
     * @return string
     */
    public function store(StoreStaff $request)
    {
        return Staff::create([
            'code' => $request->code,
            'type_id' => StaffType::findByName($request->type)->id,
            'specialty_id' => $request->specialty,
            'family_id' => $request->family,
            'user_id' => $request->holder,
            'notes' => $request->notes
        ])->load('type','specialty','family','user');
    }


    /**
     * Destroy.
     *
     * @param DeleteStaff $request
     * @param $tenant
     * @param Staff $staff
     * @return bool|null
     */
    public function destroy(DeleteStaff $request, $tenant, Staff $staff)
    {
        $staff->delete();
        return $staff;
    }
}
