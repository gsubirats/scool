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
 * Class TenantUpdated.
 *
 * @package App\Events
 */
class TenantUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tenant;

    /**
     * TenantUpdated constructor.
     *
     * @param $tenant
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
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
