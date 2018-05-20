<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Tenant\Traits\PhotoSlug;
use App\Http\Requests\StoreAssignedTeacherPhoto;
use App\Models\User;

/**
 * Class AssignedTeacherPhotoController.
 *
 * @package App\Http\Controllers\Tenant
 */
class AssignedTeacherPhotoController extends Controller
{
    use PhotoSlug;

    /**
     * Store.
     *
     * @param StoreAssignedTeacherPhoto $request
     * @param $tenant
     * @param User $user
     * @return mixed
     *
     */
    public function store(StoreAssignedTeacherPhoto $request,$tenant, User $user)
    {
        $file = $this->obtainPhotoPathBySlug($tenant, $request->photo);
        return $user->assignPhoto($file, $tenant);
    }
}
