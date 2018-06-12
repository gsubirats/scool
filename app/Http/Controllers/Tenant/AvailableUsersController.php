<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ListAvailableUsers;
use App\Models\JobType;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class AvailableUsersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class AvailableUsersController extends Controller
{
    /**
     * Index.
     *
     * @param ListAvailableUsers $request
     * @param $tenant
     * @param JobType $jobType
     */
    public function index(ListAvailableUsers $request, $tenant, JobType $jobType)
    {
        return User::doesntHave('jobs')->get();
    }
}
