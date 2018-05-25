<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\SeeTeacherProfile;
use App\Models\Teacher;

/**
 * Class TeacherProfileController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class TeacherProfileController extends Controller
{
    /**
     * @param SeeTeacherProfile $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(SeeTeacherProfile $request)
    {
        $teacher = $request->user()->load('jobs','jobs.family','jobs.specialty','person', 'person.identifier',
            'person.address', 'person.address.location', 'person.address.province','teacher', 'teacher.specialty',
            'teacher.specialty.force');
        $teachers = $teachers = Teacher::all();
        return view('tenants.teacher.profile', compact('teacher','teachers'));
    }
}
