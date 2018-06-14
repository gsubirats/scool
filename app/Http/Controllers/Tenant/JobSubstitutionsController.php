<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteJobSubstitutions;
use App\Http\Requests\StoreJobSubstitution;
use App\Http\Requests\UpdateJobSubstitution;
use App\Models\Employee;
use App\Models\Job;
use Carbon\Carbon;

/**
 * Class JobSubstitutionsController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class JobSubstitutionsController extends Controller
{

    /**
     * Store.
     *
     * @param StoreJobSubstitution $request
     * @param $tenant
     * @param Job $job
     * @return mixed
     */
    public function store(StoreJobSubstitution $request,$tenant, Job $job)
    {
        return Employee::create([
            'user_id' => $request->user,
            'job_id' => $job->id,
            'start_at' => $this->normalizeDate($request->start_at),
        ]);
    }

    protected function normalizeDate($date)
    {
        $datetime = (new Carbon($date))->toDateTimeString();
        if (ends_with($datetime,'00:00:00')) {
            return $date . ' ' . Carbon::now()->toTimeString();
        }
        return $date;
    }

    /**
     * Update.
     *
     * @param UpdateJobSubstitution $request
     * @param $tenant
     * @param Job $job
     * @return mixed
     */
    public function update(UpdateJobSubstitution $request,$tenant, Job $job)
    {
        $employee = Employee::where('user_id', $request->user_id)->where('job_id', $job->id)->first();
        $request->start_at && $employee->start_at = $this->normalizeDate($request->start_at);
        if ($request->end_at) {
            $employee->end_at = $this->normalizeDate($request->end_at);
        } elseif ($employee->end_at) {
            $employee->end_at = null;
        }
        $employee->save();
        return $employee;
    }

    /**
     * Destroy
     */
    public function destroyAll(DeleteJobSubstitutions $request,$tenant, Job $job)
    {
        if ($job->substitutes()->count() > 0) {
            $job->substitutes()->detach();
        }
    }
}
