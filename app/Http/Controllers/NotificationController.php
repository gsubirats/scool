<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NotificationRead;
use App\Events\NotificationReadAll;
use App\Notifications\HelloNotification;
use NotificationChannels\WebPush\PushSubscription;

class NotificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('last', 'dismiss');
    }


    /**
     * Create a new notification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->user()->notify(new HelloNotification);

        return response()->json('Notification sent.', 201);
    }


}
