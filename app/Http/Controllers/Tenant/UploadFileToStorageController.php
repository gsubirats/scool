<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;

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
}
