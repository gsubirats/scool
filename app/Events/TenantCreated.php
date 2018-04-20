<?php

namespace App\Events;

use App\Tenant;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

/**
 * Class TenantCreated.
 *
 * @package App\Events
 */
class TenantCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tenant;

    /**
     * TenantCreated constructor.
     *
     * @param $tenant
     */
    public function __construct(Tenant $tenant)
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
