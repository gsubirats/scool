<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotoAssigned;
use App\Events\TeacherPhotoUnassigned;
use App\Http\Controllers\Tenant\Traits\PhotoSlug;
use App\Http\Requests\DeleteAssignedTeacherPhoto;
use App\Http\Requests\StoreAllAssignedTeacherPhoto;
use App\Http\Requests\StoreAssignedTeacherPhoto;
use App\Models\Teacher;
use App\Models\User;
use File;
use Storage;

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
     * Store all.
     *
     * @param StoreAllAssignedTeacherPhoto $request
     * @param $tenant
     * @return int
     */
    public function storeAll(StoreAllAssignedTeacherPhoto $request, $tenant)
    {
        return autoassign_photos_to_teachers(Storage::path($tenant . '/teacher_photos'), $tenant);
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
        $name = $user->teacher->code . '_' . str_slug($user->name) . '_' . str_slug($user->email);
        $destPath = $tenant . '/teacher_photos/' . $name;
        $ext = pathinfo($user->photo, PATHINFO_EXTENSION);
        if($ext) {
            $destPath = $destPath . '.' . $ext;
        }
        $result = $user->unassignPhoto($destPath);
        event(new TeacherPhotoUnassigned($user,$destPath));
        return $result;
    }
}
