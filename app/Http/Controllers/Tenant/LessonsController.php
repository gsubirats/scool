<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowLessonsManager;
use App\Models\Lesson;
use App\Models\Subject;

/**
 * Class LessonsController.
 *
 * @package App\Http\Controllers\Tenant
 */
class LessonsController extends Controller
{

    /**
     * Show.
     *
     * @param ShowLessonsManager $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowLessonsManager $request)
    {
        $subjects = Subject::all();
        $lessons = Lesson::all();
        return view('tenants.lessons.show',compact(['subjects','lessons']));
    }
}
