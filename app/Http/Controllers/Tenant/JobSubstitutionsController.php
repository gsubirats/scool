<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteJobSubstitutions;
use App\Http\Requests\StoreJobSubstitution;
use App\Http\Requests\UpdateJobSubstitution;
use App\Models\Employee;
use App\Models\Job;

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
            'start_at' => $request->start_at,
        ]);
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
//        dump($employee->start_at);
//        dump($employee->end_at);
//        dump($request->start_at);
        $employee->start_at = $request->start_at;
//        dump($request->end_at);
        $employee->end_at = $request->end_at;
        $employee->save();
//        dump($employee->start_at);
//        dump($employee->start_at->toDatetimeString());
//        dump($employee->end_at);
//        dump($employee->end_at->toDatetimeString());
//        dump('HEY:');
//        dump(json_encode($employee));
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
