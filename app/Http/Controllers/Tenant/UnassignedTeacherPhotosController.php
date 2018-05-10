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
    public function store(StoreUnassignedTeacherPhotos $request, $tenant)
    {
        $path = $request->file('photos')->storeAs($this->basePathZip($tenant),$request->file('photos')->getClientOriginalName());
        event(new TeacherPhotosZipUploaded($path, $tenant));
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
        return response()->download($this->obtainZipBySlug($zip_slug,$tenant)->getPathName());
    }

    /**
     * Download all.
     *
     * @param DownloadUnassignedTeacherPhotos $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadAll(DownloadUnassignedTeacherPhotos $request, $tenant)
    {
        $files = glob(Storage::path($this->basePath($tenant)) . '/*');
        $path = Storage::path($this->basePathZip($tenant)).'/teachers_' . str_slug(Carbon::now(),'-') . '.zip';
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
        $file = $this->obtainZipBySlug($zip_slug, $tenant);
        Storage::delete($this->basePathZip($tenant). '/' . $file->getFileName());
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
    public function destroyAll(DeleteAllUnassignedTeacherPhotos $request, $tenant)
    {
        $files = File::allFiles(Storage::path($this->basePath($tenant)));
        foreach ($files as $file)
        {
            Storage::delete($this->basePath($tenant) . '/' . $file->getFilename());
        }
    }
    /**
     * Obtain photo by slug.
     *
     * @param $slug
     * @return mixed
     */
    protected function obtainZipBySlug($slug, $tenant)
    {
        $files = collect(File::allFiles(Storage::disk('local')->path($this->basePathZip($tenant))))->map(function ($photo) {
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
