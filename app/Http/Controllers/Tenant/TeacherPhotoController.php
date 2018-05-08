<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\ShowTeacherPhoto;
use App\Http\Requests\StoreTeacherPhoto;
use App\Models\User;
use File;

/**
 * Class TeacherPhotoController.
 *
 * @package App\Http\Controllers\Tenant
 */
class TeacherPhotoController extends Controller
{
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
     * Obtain photo by slug.
     *
     * @param $slug
     * @return mixed
     */
    protected function obtainPhotoBySlug($slug)
    {
        $photos = collect(File::allFiles(storage_path('photos/teachers')))->map(function ($photo) {
            return [
                'file' => $photo,
                'filename' => $filename = $photo->getFilename(),
                'slug' => str_slug($filename,'-')
            ];
        });
        return $photos[$photos->search(function ($photo) use ($slug){
            return $photo['slug'] ===  $slug;
        })]['file'];
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
