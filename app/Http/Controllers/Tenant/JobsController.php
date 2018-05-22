<?php

namespace App\Http\Controllers\Tenant;

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
        $job = Job::with('type','family','specialty','user')->get();
        $jobTypes = JobType::all();
        $specialties = Specialty::with('staff','staff.family')->get();
        $families = Family::with('staff','staff.specialty')->get();
        $users = User::all();
        return view('tenants.staff.show',compact(
            'staff',
            'staffTypes',
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
        return Job::create([
            'code' => $request->code,
            'type_id' => JobType::findByName($request->type)->id,
            'specialty_id' => $request->specialty,
            'family_id' => $request->family,
            'user_id' => $request->holder,
            'order' => $request->order,
            'notes' => $request->notes
        ])->load('type','specialty','family','user');
    }


    /**
     * Destroy.
     *
     * @param DeleteJobs $request
     * @param $tenant
     * @param Job $job
     * @return bool|null
     */
    public function destroy(DeleteJobs $request, $tenant, Job $job)
    {
        $job->delete();
        return $job;
    }
}
