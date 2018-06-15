<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteJob;
use App\Http\Requests\ListJobs;
use App\Http\Requests\ShowJobsManagement;
use App\Http\Requests\StoreJob;
use App\Models\Family;
use App\Models\Specialty;
use App\Models\Job;
use App\Models\JobType;
use App\Models\Teacher;
use App\Models\User;
use App\Http\Resources\Tenant\Job as JobResource;

/**
 * Class JobsController.
 *
 * @package App\Http\Controllers\Tenant
 */
class JobsController extends Controller
{

    /**
     * Index.
     *
     * @param ListJobs $request
     * @return \Illuminate\Support\Collection
     */
    public function index(ListJobs $request)
    {
        return $this->jobs();
    }

    protected function jobs() {
        return collect(JobResource::collection(
            Job::with(
                'type',
                'family',
                'specialty',
                'users',
                'holders',
                'holders.teacher',
                'substitutes',
                'substitutes.teacher')->get()));
    }


    /**
     * Show staff management.
     *
     * @param ShowJobsManagement $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowJobsManagement $request)
    {
        $jobs =  $this->jobs();

        $jobTypes = JobType::all();
        $specialties = Specialty::with('jobs','jobs.family')->get();
        $families = Family::with('jobs','jobs.specialty')->get();
        $users = User::available();
        $nextAvailableCode = Teacher::firstAvailableCode();

        return view('tenants.jobs.show',compact(
            'jobs',
            'jobTypes',
            'specialties',
            'families',
            'users',
            'nextAvailableCode'
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
