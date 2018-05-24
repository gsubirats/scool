<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Tenant\Controller;
use App\Http\Requests\ImpersonateUser;
use App\User;
use Auth;
use Illuminate\Http\Request;

/**
 * Class ImpersonateUserController.
 *
 * @package App\Http\Controllers\Tenant\Admin
 */
class ImpersonateUserController extends Controller
{
    public function store(ImpersonateUser $request)
    {
        $result = Auth::user()->impersonate(User::findOrFail($request->user));
        if(!$result) {
            dd('error');
        } else {
            dd('ok');
        }
    }
}
