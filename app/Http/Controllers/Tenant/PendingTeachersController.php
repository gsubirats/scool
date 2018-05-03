<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Specialty;
use Illuminate\Http\Request;

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
        return view ('tenants.teacher.show_pending_teacher', compact('specialties'));
    }
}
