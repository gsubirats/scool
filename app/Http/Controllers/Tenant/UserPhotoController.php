<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use Illuminate\Http\Request;
use Storage;

/**
 * Class UserPhotoController.
 *
 * @package App\Http\Controllers
 */
class UserPhotoController extends Controller
{
    /**
     * Show user photo.
     *
     * @param Request $request
     * @param $tenant
     * @param User $user
     */
    public function show(Request $request, $tenant, User $user)
    {
        $photoPath = $this->basePath($tenant , $user->photo);
        if (! $user->photo || ! Storage::disk('local')->exists($user->photo)) {
            return response()->file(Storage::disk('local')->path(
                $this->basePath($tenant,User::DEFAULT_PHOTO_PATH)));
        }
        return response()->file(Storage::disk('local')->path($user->photo));
    }

    /**
     * Tenant base path
     *
     * @param $tenant
     * @param $path
     * @return string
     */
    protected function basePath($tenant , $path)
    {
        return $tenant. '/' . $path;
    }
}
