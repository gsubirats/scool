<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowLessonsManager;
use App\Http\Requests\StoreSubjectLessonsCalculateController;
use App\Models\Lesson;
use App\Models\Subject;
use App\Models\SubjectGroup;
use App\Models\WeekLesson;
use Carbon\Carbon;

/**
 * Class SubjectLessonsCalculateController.
 *
 * @package App\Http\Controllers\Tenant
 */
class SubjectLessonsCalculateController extends Controller
{
    public function store(StoreSubjectLessonsCalculateController $request,$tenant, Subject $subject)
    {
        $minutes = $subject->hours*60;
        $subject_group = $subject->subject_group;
        $start= new Carbon($subject_group->start);
        $original_week_lessons = $subject_group->week_lessons;

        $real_starts = [];
        $real_ends = [];
        foreach ($original_week_lessons as $original_week_lesson) {
            if ($start->isDayOfWeek($original_week_lesson->day)) {
                $real_starts[] = (new Carbon($subject_group->start))->modify($original_week_lesson->start);
                $real_ends[] = (new Carbon($subject_group->start))->modify($original_week_lesson->end);
            } else {
                $real_starts[] = (new Carbon($subject_group->start))->modify('next ' . dayOfWeek($original_week_lesson->day))->modify($original_week_lesson->start);
                $real_ends[] = (new Carbon($subject_group->start))->modify('next ' . dayOfWeek($original_week_lesson->day))->modify($original_week_lesson->end);
            }
        }
        $totalNumberOfWeekLessons = count($original_week_lessons);
        $week_lessons = $original_week_lessons;
        $firstTime = true;

        $week_lesson_number = 1;
        $counter = 1;
        $last_lesson_start = [];
        $last_lesson_end = [];
        while ($minutes > 0) {
            $week_lesson = $week_lessons[$week_lesson_number-1];
            if ($firstTime) {
                $lesson_start = $real_starts[$week_lesson_number-1];
                $lesson_end = $real_ends[$week_lesson_number-1];
                if($week_lesson_number === $totalNumberOfWeekLessons) $firstTime = false;
            } else {
                $lesson_start = (new Carbon($last_lesson_start[$week_lesson_number]))->modify('next ' . dayOfWeek($week_lesson->day))->modify($week_lesson->start);
                $lesson_end = (new Carbon($last_lesson_end[$week_lesson_number]))->modify('next ' . dayOfWeek($week_lesson->day))->modify($week_lesson->end);
            }
//            dump('######################################');
//            dump('Iteration: ' . $counter);
//            dump('lesson_start: ' . $lesson_start);
//            dump('lesson_end: ' . $lesson_end);
//            dump('minuts restants: ' . $minutes);
            $lessonMinutes = $lesson_start->diffInMinutes(new Carbon($lesson_end));
//            dump('lesson minutes: ' . $lessonMinutes);
            Lesson::create([
                'subject_id' => $subject->id,
                'start' => $lesson_start,
                'end' => $lesson_end
            ]);
            $minutes = $minutes - $lessonMinutes;
            $last_lesson_start[$week_lesson_number] = $lesson_start;
            $last_lesson_end[$week_lesson_number] = $lesson_end;
            $counter++;
            if ($week_lesson_number == $totalNumberOfWeekLessons) $week_lesson_number= 1;
            else $week_lesson_number++;
        }

//        dump($subject->hours);
//        dump('TOTAL ITERATIONS: ' . $counter);
//        echo (new Carbon('first day of December 2008'))->addWeeks(2);     // 2008-12-15 00:00:00


    }
}
