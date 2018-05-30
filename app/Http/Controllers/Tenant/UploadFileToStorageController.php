<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Storage;

/**
 * Class UploadFileToStorageController.
 *
 * @package App\Http\Controllers\Tenant
 */
class UploadFileToStorageController extends Controller
{
    /**
     * Store.
     *
     * @param Request $request
     * @return false|string
     */
    public function store(Request $request, $tenant, $storage)
    {
        $path = $request->file('file')->store($tenant . '/uploads',$storage);

        return $path;
    }

    /**
     * Destroy.
     *
     * @param Request $request
     * @param $tenant
     * @param $storage
     */
    public function destroy(Request $request, $tenant, $storage)
    {
        if( !starts_with($request->path,$tenant . '/uploads')) abort(403);
        Storage::disk($storage)->delete($request->path);
    }
}
