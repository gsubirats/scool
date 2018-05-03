<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;

/**
 * Class PendingTeachersController.
 *
 * @package App\Http\Controllers\Tenant
 */
class PendingTeachersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        return view ('tenants.teacher.show_pending_teacher');
    }
}
