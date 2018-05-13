<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowTeachersManagment;
use App\Models\AdministrativeStatus;
use App\Models\Force;
use App\Models\PendingTeacher;
use App\Models\Specialty;
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
        $teachers = $this->teachers();
        $specialties = Specialty::all();
        $forces = Force::all();
        $administrative_statuses = AdministrativeStatus::all();
        return view('tenants.teachers.show', compact(
            'pendingTeachers','teachers','specialties','forces','administrative_statuses'));
    }

    protected function teachers()
    {
        // Criteris:
        // Usuaris amb Rol Professor/a i plaça assignada
        return User::all();
    }
}
