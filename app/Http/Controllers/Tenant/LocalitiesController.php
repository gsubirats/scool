<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Location;
use Illuminate\Http\Request;

/**
 * Class LocalitiesController.
 *
 * @package App\Http\Controllers\Tenant
 */
class LocalitiesController extends Controller
{
    public function index()
    {
        return Location::all();
    }
}
