<?php

namespace App\Http\Controllers\Tenant;
use App\Events\UnassignedTeacherPhotoUploaded;
use App\Http\Requests\StoreUnassignedTeacherPhoto;

/**
 * Class UnassignedTeacherPhotoController.
 *
 * @package App\Http\Controllers
 */
class UnassignedTeacherPhotoController extends Controller
{
    /**
     * Store.
     *
     * @param StoreUnassignedTeacherPhoto $request
     * @return false|string
     */
    public function store(StoreUnassignedTeacherPhoto $request)
    {
        $path = $request->file('teacher_photo')->store('teacher_photos');
        event(new UnassignedTeacherPhotoUploaded($path));
        return $path;
    }
}
