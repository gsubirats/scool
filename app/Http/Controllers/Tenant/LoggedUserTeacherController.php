<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Teacher;
use Illuminate\Http\Request;

/**
 * Class LoggedUserTeacherController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class LoggedUserTeacherController extends Controller
{
    /**
     * Show.
     *
     * @param Request $request
     * @return array
     */
    public function show(Request $request) {
        $teacher = Teacher::where('user_id', $request->user()->id)->firstOrFail();

        $teacher->load(
            'specialty',
            'specialty.force',
            'specialty.family',
            'administrativeStatus',
            'user',
            'user.jobs',
            'user.jobs.family',
            'user.jobs.specialty',
            'user.person',
            'user.person.birthplace',
            'user.person.identifier',
            'user.person.address',
            'user.person.address.province',
            'user.person.address.location');
        return $teacher;
    }
}
