<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeletePendingTeacher;
use App\Http\Requests\ListPendingTeachers;
use App\Http\Requests\SeePendingTeachers;
use App\Http\Requests\StorePendingTeacher;
use App\Models\AdministrativeStatus;
use App\Models\Force;
use App\Models\PendingTeacher;
use App\Models\Specialty;
use App\Models\User;

/**
 * Class PendingTeachersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class PendingTeachersController extends Controller
{
    /**
     * Show.
     *
     * @param SeePendingTeachers $request
     * @param $tenant
     * @param PendingTeacher $teacher
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(SeePendingTeachers $request, $tenant, PendingTeacher $teacher)
    {

        $specialties = Specialty::all();
        $forces = Force::all();
        $administrative_statuses = AdministrativeStatus::all();
        return view ('tenants.teacher.pending.show',
            compact('specialties','forces','administrative_statuses','teacher'));
    }

    /**
     * Show form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showForm()
    {
        $specialties = Specialty::all();
        $forces = Force::all();
        $administrative_statuses = AdministrativeStatus::all();
        $teachers = User::teachers()->get();
        return view ('tenants.teacher.pending.show_form',
            compact('specialties','forces','administrative_statuses','teachers'));
    }

    /**
     * @param ListPendingTeachers $request.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index(ListPendingTeachers $request)
    {
        return PendingTeacher::all();
    }

    /**
     * Store pending teacher.
     *
     * @param StorePendingTeacher $request
     * @return mixed
     */
    public function store(StorePendingTeacher $request)
    {
        return PendingTeacher::create($request->only([
            'name',
            'sn1',
            'sn2',
            'identifier',
            'birthdate',
            'street',
            'number',
            'floor',
            'floor_number',
            'postal_code',
            'locality',
            'province',
            'email',
            'other_emails',
            'phone',
            'other_phones',
            'mobile',
            'other_mobiles',
            'degree',
            'other_degrees',
            'languages',
            'profiles',
            'other_training',
            'force_id',
            'specialty_id',
            'teacher_start_date',
            'start_date',
            'opositions_date',
            'administrative_status_id',
            'destination_place',
            'teacher_id',
            'photo',
            'identifier_photocopy'
        ]));
    }

    public function destroy(DeletePendingTeacher $request, $tenant, PendingTeacher $teacher)
    {
        $teacher->delete();
        return $teacher;
    }
}
