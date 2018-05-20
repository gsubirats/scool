<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotoAssigned;
use App\Events\TeacherPhotoUnassigned;
use App\Http\Controllers\Tenant\Traits\PhotoSlug;
use App\Http\Requests\DeleteAssignedTeacherPhoto;
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
    public function store(StoreAssignedTeacherPhoto $request, $tenant, User $user)
    {
        $file = $this->obtainPhotoBySlug($tenant, $request->photo);
        $result = $user->assignPhoto($file, $tenant);
        event(new TeacherPhotoAssigned($user, $result->photo));
        return $result;
    }

    /**
     * Delete.
     *
     * @param DeleteAssignedTeacherPhoto $request
     * @param $tenant
     * @param User $user
     * @return $this
     */
    public function delete(DeleteAssignedTeacherPhoto $request, $tenant, User $user)
    {
        $destPath = $tenant . '/teacher_photos/' . $user->photo_name;
        $ext = pathinfo($user->photo, PATHINFO_EXTENSION);
        if($ext) {
            $destPath = $destPath . '.' . $ext;
        }
        $photo = $user->photo;
        $result = $user->unassignPhoto($destPath);
        event(new TeacherPhotoUnassigned($user,$photo));
        return $result;
    }
}
