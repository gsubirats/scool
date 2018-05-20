<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Tenant\Traits\PhotoSlug;
use App\Http\Requests\DownloadTeacherPhoto;
use App\Http\Requests\EditTeacherPhoto;
use App\Http\Requests\ShowTeacherPhoto;
use App\Http\Requests\StoreTeacherPhoto;
use App\Models\User;
use Storage;

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
        return response()->file($this->obtainPhotoBySlug($tenant . '/teacher_photos', $photo_slug)->getPathName());
    }

    /**
     * Download.
     *
     * @param DownloadTeacherPhoto $request
     * @param $tenant
     * @param $photo_slug
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(DownloadTeacherPhoto $request, $tenant, $photo_slug)
    {
        return response()->download($this->obtainPhotoBySlug($tenant . '/teacher_photos',$photo_slug)->getPathName());
    }

    /**
     * Edit.
     *
     * @param EditTeacherPhoto $request
     * @param $tenant
     * @param $photo_slug
     * @return string
     */
    public function edit(EditTeacherPhoto $request, $tenant, $photo_slug)
    {
        $file = $this->obtainPhotoBySlug($tenant . '/teacher_photos', $photo_slug);
        Storage::move( $this->basePath($tenant) . $file->getFilename(), $this->basePath($tenant) . $request->filename);
        return str_slug($request->filename,'-');
    }

    /**
     * Store.
     *
     * @param StoreTeacherPhoto $request
     * @param $tenant
     * @return mixed
     */
    public function store(StoreTeacherPhoto $request, $tenant)
    {
        $path = $request->file('photo')->store($this->basePath($tenant));

        $user = User::findOrFail($request->user_id);

        $user->photo = $path;
        $user->save();

        return $user;
    }

    /**
     * Base path.
     *
     * @param $tenant
     * @return string
     */
    protected function basePath($tenant)
    {
     return $tenant . '/teacher_photos/';
    }
}
