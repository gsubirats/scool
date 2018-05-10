<?php

namespace App\Http\Controllers\Tenant;

use App\Events\TeacherPhotosZipUploaded;
use App\Http\Requests\DeleteAllUnassignedTeacherPhotos;
use App\Http\Requests\DeleteUnassignedTeacherPhotos;
use App\Http\Requests\DownloadUnassignedTeacherPhotos;
use App\Http\Requests\StoreUnassignedTeacherPhotos;
use Carbon\Carbon;
use Chumper\Zipper\Facades\Zipper;
use File;
use Storage;

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
        return [
            'path' => $path,
            'filename' => basename($path),
            'slug' => str_slug(basename($path))
        ];
    }

    /**
     * Download.
     *
     * @param DownloadUnassignedTeacherPhotos $request
     * @param $tenant
     * @param $zip_slug
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(DownloadUnassignedTeacherPhotos $request, $tenant, $zip_slug)
    {
        return response()->download($this->obtainZipBySlug($zip_slug)->getPathName());
    }

    /**
     * Download all.
     *
     * @param DownloadUnassignedTeacherPhotos $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadAll(DownloadUnassignedTeacherPhotos $request)
    {
        $files = glob(Storage::path('teacher_photos') . '/*');
        $path = Storage::path('teacher_photos_zip').'/teachers_' . str_slug(Carbon::now(),'-') . '.zip';
        Zipper::make($path)->add($files)->close();
        return response()->download($path);
    }

    /**
     * Destroy
     *
     * @param DeleteUnassignedTeacherPhotos $request
     * @param $tenant
     * @param $zip_slug
     * @return array
     */
    public function destroy(DeleteUnassignedTeacherPhotos $request, $tenant, $zip_slug)
    {
        $file = $this->obtainZipBySlug($zip_slug);
        Storage::delete('teacher_photos_zip/' . $file->getFileName());
        return [
          'path' => $file->getPath(),
          'slug' => str_slug($file->getFilename(),'-'),
          'filename' => $file->getFilename(),
        ];
    }

    /**
     * Destroy all.
     *
     * @param DeleteAllUnassignedTeacherPhotos $request
     */
    public function destroyAll(DeleteAllUnassignedTeacherPhotos $request)
    {
        $files = File::allFiles(Storage::path('teacher_photos'));
        foreach ($files as $file)
        {
            Storage::delete('teacher_photos/' . $file->getFilename());
        }
    }
    /**
     * Obtain photo by slug.
     *
     * @param $slug
     * @return mixed
     */
    protected function obtainZipBySlug($slug)
    {
        $files = collect(File::allFiles(Storage::disk('local')->path('teacher_photos_zip')))->map(function ($photo) {
            return [
                'file' => $photo,
                'filename' => $filename = $photo->getFilename(),
                'slug' => str_slug($filename,'-')
            ];
        });

        $found = $files->search(function ($file) use ($slug){
            return $file['slug'] ===  $slug;
        });

        if ($found === false) abort('404',"No s'ha trobat cap fitxer amb l'slug: $slug");

        return $files[$found]['file'];
    }
}
