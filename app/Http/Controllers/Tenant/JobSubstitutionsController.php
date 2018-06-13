<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteJobSubstitutions;
use App\Http\Requests\StoreJobSubstitution;
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
     * Destroy
     */
    public function destroyAll(DeleteJobSubstitutions $request,$tenant, Job $job)
    {
        if ($job->substitutes()->count() > 0) {
            $job->substitutes()->detach();
        }
    }
}
