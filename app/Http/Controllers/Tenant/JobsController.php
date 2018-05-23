<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteJob;
use App\Http\Requests\ShowJobsManagement;
use App\Http\Requests\StoreJob;
use App\Models\Family;
use App\Models\Specialty;
use App\Models\Job;
use App\Models\JobType;
use App\Models\User;

/**
 * Class JobsController.
 *
 * @package App\Http\Controllers\Tenant
 */
class JobsController extends Controller
{
    /**
     * Show staff management.
     *
     * @param ShowJobsManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowJobsManagement $request)
    {
        $jobs = Job::with('type','family','specialty','users')->get();
        $jobTypes = JobType::all();
        $specialties = Specialty::with('jobs','jobs.family')->get();
        $families = Family::with('jobs','jobs.specialty')->get();
        $users = User::all();
        return view('tenants.jobs.show',compact(
            'jobs',
            'jobTypes',
            'specialties',
            'families',
            'users'
        ));
    }

    /**
     * Store.
     *
     * @return string
     */
    public function store(StoreJob $request)
    {
        $job = Job::create([
            'code' => $request->code,
            'type_id' => JobType::findByName($request->type)->id,
            'specialty_id' => $request->specialty,
            'family_id' => $request->family,
            'order' => $request->order,
            'notes' => $request->notes
        ])->load('type','specialty','family','users');

        $job->users()->save(User::findOrFail($request->holder));
        return $job;
    }

    /**
     * Destroy.
     *
     * @param DeleteJob $request
     * @param $tenant
     * @param Job $job
     * @return Job
     */
    public function destroy(DeleteJob $request, $tenant, Job $job)
    {
        $job->delete();
        return $job;
    }
}
