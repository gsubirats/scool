<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\DeleteJobSubstitutions;
use App\Models\Job;
use Illuminate\Http\Request;

/**
 * Class JobSubstitutionsController.
 * 
 * @package App\Http\Controllers\Tenant
 */
class JobSubstitutionsController extends Controller
{
    /**
     * Destroy
     */
    public function destroy(DeleteJobSubstitutions $request,$tenant, Job $job)
    {
        if ($job->substitutes()->count() > 0) {
            $job->substitutes()->detach();
        }
    }
}
