<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowTeachersManagment;
use App\Models\AdministrativeStatus;
use App\Models\Force;
use App\Models\PendingTeacher;
use App\Models\Specialty;
use App\Models\Teacher;
use App\Http\Resources\Teacher as TeacherResource;

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

        $teachers =  collect(TeacherResource::collection(
            Teacher::with([
                'specialty',
                'specialty.force','specialty.family','administrativeStatus','user', 'user.jobs',
                'user.jobs.specialty','user.jobs.family','user.jobs.users','user.jobs.holders',
                'user.person','user.person.birthplace',
                'user.person.identifier','user.person.address','user.person.address.province',
                'user.person.address.location','department'
            ])->orderByRaw('code + 0')->get()));

        // TODO
//        dd($teachers->toArray());
//        dd($teachers);
//
//        $teachers = Teacher::with([
//            'specialty',
//            'specialty.force','specialty.family','administrativeStatus','user', 'user.jobs',
//            'user.jobs.specialty','user.jobs.family','user.jobs.users','user.jobs.holders',
//            'user.person','user.person.birthplace',
//            'user.person.identifier','user.person.address','user.person.address.province',
//            'user.person.address.location','department'
//        ])->orderByRaw('code + 0')->get();
//        dd($teachers);
        $specialties = Specialty::all();
        $forces = Force::all();
        $administrativeStatuses = AdministrativeStatus::all();
        return view('tenants.teachers.show', compact(
            'pendingTeachers','teachers','specialties','forces','administrativeStatuses'));
    }
}
