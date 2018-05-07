<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowTeachersPhotosManagment;
use App\Http\Requests\StoreTeachersPhotosManagment;
use Illuminate\Http\Request;

/**
 * Class TeachersPhotosController.
 *
 * @package App\Http\Controllers
 */
class TeachersPhotosController extends Controller
{
    /**
     * Show.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowTeachersPhotosManagment $request)
    {
        $teachers = [];
        return view('tenants.teachers.photos.show', compact('pendingTeachers','teachers'));
    }

    public function store(StoreTeachersPhotosManagment $request)
    {
        $path = $request->file('teacher_photos')->store('teacher_photos');

        return $path;
    }
}
