<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowTeachersManagment;
use App\Models\AdministrativeStatus;
use App\Models\Force;
use App\Models\PendingTeacher;
use App\Models\Specialty;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class TeachersController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class TeachersController extends Controller
{
    /**
     * Show teachers.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowTeachersManagment $request)
    {
        $pendingTeachers = PendingTeacher::with('specialty')->get();
        $teachers = Teacher::with(['specialty','specialty.force','specialty.family','administrativeStatus','user','user.staffs','user.person','user.person.birthplace',
            'user.person.identifier','user.person.address','user.person.address.province',
            'user.person.address.location'])->orderByRaw('code + 0')->get();
        $specialties = Specialty::all();
        $forces = Force::all();
        $administrative_statuses = AdministrativeStatus::all();
        return view('tenants.teachers.show', compact(
            'pendingTeachers','teachers','specialties','forces','administrative_statuses'));
    }
}
