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

    public $passsword;

    /**
     * TenantCreated constructor.
     * @param $tenant
     * @param $passsword
     */
    public function __construct($tenant, $passsword)
    {
        $this->tenant = $tenant;
        $this->passsword = $passsword;
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
