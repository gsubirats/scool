<?php

namespace App\Http\Controllers\Tenant;

use App\Models\AdministrativeStatus;
use App\Models\Force;
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
}
