<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotosUploaded;
use App\Http\Requests\ShowTeacherPhoto;
use App\Http\Requests\ShowTeachersPhotosManagment;
use App\Http\Requests\StoreTeachersPhotosManagment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

/**
 * Class TeachersPhotosController.
 *
 * @package App\Http\Controllers
 */
class TeachersPhotosController extends Controller
{
    /**
     * Show.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(ShowTeachersPhotosManagment $request)
    {

        $photos = collect(File::allFiles(storage_path('photos/teachers')))->map(function ($photo) {
                return [
                  'filename' => $filename = $photo->getFilename(),
                  'slug' => str_slug($filename,'-')
                ];
            });

        return view('tenants.teachers.photos.show', compact('photos'));
    }

    /**
     * Show photo
     */
    public function showPhoto(ShowTeacherPhoto $request, $tenant, $photo_slug)
    {
        $photo = $this->obtainPhotoBySlug($photo_slug);
        return response()->file($photo->getPathName());
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
//        dd($photos);
        return $photos[$photos->search(function ($photo) use ($slug){
            return $photo['slug'] ===  $slug;
        })]['file'];
    }

    /**
     * Store file.
     *
     * @param StoreTeachersPhotosManagment $request
     * @return false|string
     */
    public function store(StoreTeachersPhotosManagment $request)
    {
        $path = $request->file('teacher_photos')->store('teacher_photos');

        event(new TeacherPhotosUploaded($path));

//        https://github.com/Chumper/Zipper
//        $zip = new ZipArchive;
//        $res = $zip->open($path);
//        if ($res === TRUE) {
//            $zip->extractTo('/myzips/extract_path/');
//            $zip->close();
//        } else {
//            dd('File is not a zip');
//        }

        return $path;
    }
}
