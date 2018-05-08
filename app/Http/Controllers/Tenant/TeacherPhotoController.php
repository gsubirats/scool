<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Tenant\Traits\PhotoSlug;
use App\Http\Requests\ShowTeacherPhoto;
use App\Http\Requests\StoreTeacherPhoto;
use App\Models\User;

/**
 * Class TeacherPhotoController.
 *
 * @package App\Http\Controllers\Tenant
 */
class TeacherPhotoController extends Controller
{
    use PhotoSlug;

    /**
     * Show photo.
     *
     * @param ShowTeacherPhoto $request
     * @param $tenant
     * @param $photo_slug
     * @return mixed
     */
    public function show(ShowTeacherPhoto $request, $tenant, $photo_slug)
    {
        return response()->file($this->obtainPhotoBySlug($photo_slug)->getPathName());
    }

    /**
     * Store.
     *
     * @param StoreTeacherPhoto $request
     * @return false|string
     */
    public function store(StoreTeacherPhoto $request)
    {
        $path = $request->file('photo')->store('teacher_photos');

        $user = User::findOrFail($request->user_id);

        $user->photo = $path;
        $user->save();

        return $user;
    }
}
