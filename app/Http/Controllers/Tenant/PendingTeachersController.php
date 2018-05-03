<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListPendingTeachers;
use App\Http\Requests\StorePendingTeacher;
use App\Models\AdministrativeStatus;
use App\Models\Force;
use App\Models\PendingTeacher;
use App\Models\Specialty;

/**
 * Class PendingTeachersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class PendingTeachersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $specialties = Specialty::all();
        $forces = Force::all();
        $administrative_statuses = AdministrativeStatus::all();
        return view ('tenants.teacher.show_pending_teacher',
            compact('specialties','forces','administrative_statuses'));
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
            'telephone',
            'other_telephones',
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
            'teacher_id'
        ]));
    }
}
