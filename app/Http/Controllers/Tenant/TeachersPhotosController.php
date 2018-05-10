<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotosUploaded;
use App\Http\Requests\ShowTeachersPhotosManagment;
use App\Http\Requests\StoreTeachersPhotosManagment;
use Illuminate\Support\Facades\File;
use Storage;

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
        $photos = collect_files('teacher_photos');
        $zips = collect_files('teacher_photos_zip');

        return view('tenants.teachers.photos.show', compact('photos','zips'));
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
