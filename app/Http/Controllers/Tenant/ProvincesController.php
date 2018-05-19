<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Province;

/**
 * Class ProvincesController.
 *
 * @package App\Http\Controllers\Tenant
 */
class ProvincesController extends Controller
{
    /**
     * Index.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Province::all();
    }
}
