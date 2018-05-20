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
 * Class TeacherPhotoUnassigned.
 *
 * @package App\Events
 */
class TeacherPhotoUnassigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $photo;

    /**
     * TeacherPhotoUnassigned constructor.
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
