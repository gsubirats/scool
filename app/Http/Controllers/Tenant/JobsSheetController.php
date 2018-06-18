<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Job;
use App\Http\Resources\Tenant\JobForSheet as JobResource;

/**
 * Class JobsSheetController.
 *
 * @package App\Http\Controllers\Tenant
 */
class JobsSheetController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $jobs = collect(JobResource::collection(
        Job::with(
            'holders.teacher',
            'users'
            )->get()));
        return view('tenants.jobs.sheet', compact('jobs'));
    }
}
