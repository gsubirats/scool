<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotosUploaded;
use App\Http\Requests\ShowTeachersPhotosManagment;
use App\Http\Requests\StoreTeachersPhotosManagment;
use Illuminate\Support\Facades\File;

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
