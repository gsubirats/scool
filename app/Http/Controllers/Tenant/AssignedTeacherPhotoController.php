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

    public function store(StoreAssignedTeacherPhoto $request,$tenant, User $user)
    {
        $file = $this->obtainPhotoPathBySlug($tenant, $request->photo);
        dump('FIle path');
        dd($file);
        $user->assignPhoto($file, $tenant);
    }
}
