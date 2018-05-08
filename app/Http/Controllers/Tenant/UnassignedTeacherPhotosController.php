<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotosZipUploaded;
use App\Http\Requests\StoreUnassignedTeacherPhotos;

/**
 * Class UnassignedTeacherPhotosController.
 *
 * @package App\Http\Controllers\Tenant
 */
class UnassignedTeacherPhotosController extends Controller
{
    /**
     * Store.
     *
     * @param StoreUnassignedTeacherPhotos $request
     * @return false|string
     */
    public function store(StoreUnassignedTeacherPhotos $request)
    {
        $path = $request->file('photos')->storeAs('teacher_photos_zip',$request->file('photos')->getClientOriginalName());
        event(new TeacherPhotosZipUploaded($path));
        return $path;
    }
}
