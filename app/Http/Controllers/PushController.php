<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class PushController.
 *
 * @package App\Http\Controllers
 */
class PushController extends Controller
{
    public function index()
    {
        return view('push');
    }
}
