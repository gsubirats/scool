<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class TeacherPhotoAssigned.
 *
 * @package App\Events
 */
class TeacherPhotoAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $photo;

    /**
     * TeacherPhotoAssigned constructor.
     * @param $user
     * @param $photo
     */
    public function __construct($user, $photo)
    {
        $this->user = $user;
        $this->photo = $photo;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
