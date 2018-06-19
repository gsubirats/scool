<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Job;
use App\Http\Resources\Tenant\JobForSheet as JobResource;
use App\Http\Resources\Tenant\JobForSheetHolders as JobResourceForHoders;


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
            )->orderBy('code')->get()));
        return view('tenants.jobs.sheet', compact('jobs'));
    }

    public function showHolders()
    {
        $jobs = collect(JobResourceForHoders::collection(
            Job::with(
                'holders.teacher',
                'users'
            )->orderBy('code')->get()));
        return view('tenants.jobs.sheet_holders', compact('jobs'));
    }
}
