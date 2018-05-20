<?php

namespace App\Http\Controllers\Tenant;

use App\Events\UnassignedTeacherPhotoUploaded;
use App\Http\Controllers\Tenant\Traits\PhotoSlug;
use App\Http\Requests\DestroyUnassignedTeacherPhoto;
use App\Http\Requests\ListUnassignedTeacherPhoto;
use App\Http\Requests\StoreUnassignedTeacherPhoto;
use File;
use Storage;

/**
 * Class UnassignedTeacherPhotoController.
 *
 * @package App\Http\Controllers
 */
class UnassignedTeacherPhotoController extends Controller
{
    use PhotoSlug;

    /**
     * List.
     *
     * @param ListUnassignedTeacherPhoto $request
     * @return static
     */
    public function index(ListUnassignedTeacherPhoto $request, $tenant)
    {
        $photos = collect(File::allFiles(
            Storage::disk('local')->path($this->basePath($tenant))))->map(function ($photo) {
                return [
                    'filename' => $filename = $photo->getFilename(),
                    'slug' => str_slug($filename,'-')
                ];
        });
        return $photos;
    }

    /**
     * Store.
     *
     * @param StoreUnassignedTeacherPhoto $request
     * @return false|string
     */
    public function store(StoreUnassignedTeacherPhoto $request, $tenant)
    {
        $path = $request->file('teacher_photo')->storeAs($this->basePath($tenant),$request->file('teacher_photo')->getClientOriginalName());
        event(new UnassignedTeacherPhotoUploaded($path));
        return [
           'path' => $path,
           'filename' => basename($path),
           'slug' => str_slug(basename($path),'-')
        ];
    }

    /**
     * Destroy.
     */
    public function destroy(DestroyUnassignedTeacherPhoto $request, $tenant, $slug)
    {
        $file = $this->obtainPhotoBySlug($tenant . '/teacher_photos', $slug);

        Storage::disk('local')->delete($this->basePath($tenant) . '/' . $file->getFileName());
        return [
          'filename' => $file->getFileName(),
          'slug' => $slug
        ];
    }

    /**
     * Base path.
     *
     * @param $tenant
     * @return string
     */
    protected function basePath($tenant)
    {
        return $tenant .'/teacher_photos';
    }
}
