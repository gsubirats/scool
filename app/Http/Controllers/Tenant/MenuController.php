<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Menu;
use Illuminate\Http\Request;

/**
 * Class MenuController.
 *
 * @package App\Http\Controllers\Tenant
 */
class MenuController extends Controller
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return Menu::All();
    }
}
