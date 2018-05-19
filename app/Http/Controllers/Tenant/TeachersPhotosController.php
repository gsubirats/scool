<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotosUploaded;
use App\Http\Requests\ShowTeachersPhotosManagment;
use App\Http\Requests\StoreTeachersPhotosManagment;
use App\Models\StaffType;
use App\Models\User;
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
    public function show(ShowTeachersPhotosManagment $request, $tenant)
    {
        $photos = Storage::exists($this->basePath($tenant)) ?  collect_files($this->basePath($tenant)) : collect();
        $zips = Storage::exists($this->basePathZip($tenant)) ? collect_files($this->basePathZip($tenant)) : collect();
        $teachers = User::teachers()->with('staffs')->get();

        $teachers = $this->teachersWithCode($teachers)->sort(function($a,$b) {
            $aCode = intval($a->staffs->filter(function($staff) { return $staff->type_id === 1; })[0]->code);
            $bCode = intval($b->staffs->filter(function($staff) { return $staff->type_id === 1; })[0]->code);
            if ($aCode == $bCode) {
                return 0;
            }
            return ($aCode < $bCode) ? -1 : 1;
        })->values();
        return view('tenants.teachers.photos.show', compact('photos','zips', 'teachers'));
    }

    protected function teachersWithCode($teachers)
    {
        return $teachers->map(function($teacher) {
            $teacher_staff = $teacher->staffs->filter(function($staff) {
               return $staff->type_id = StaffType::findByName('Professor/a')->id;
            });
            if ($teacher_staff[0]) {
                $teacher->code = $teacher_staff[0]->code;
                $teacher->code_number = intval($teacher_staff[0]->code);
            }
            return $teacher;
        });
    }

    /**
     * Store file.
     *
     * @param StoreTeachersPhotosManagment $request
     * @return false|string
     */
    public function store(StoreTeachersPhotosManagment $request, $tenant)
    {
        $path = $request->file('teacher_photos')->store($this->basePath($tenant));

        event(new TeacherPhotosUploaded($path));

        return $path;
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

    /**
     * Base path zip.
     *
     * @param $tenant
     * @return string
     */
    protected function basePathZip($tenant)
    {
        return $tenant .'/teacher_photos_zip';
    }
}
