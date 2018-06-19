<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowLessonsManager;
use App\Models\Lesson;
use App\Models\Subject;
use App\Http\Resources\Tenant\Lesson as LessonResource;


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
        $lessons = collect(LessonResource::collection(
                Lesson::with('subject')->get()));
        return view('tenants.lessons.show',compact(['subjects','lessons']));
    }
}
