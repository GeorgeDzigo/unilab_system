<?php

namespace App\Events\Person;

use App\Models\Person;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * @property Person person
 * @property mixed oldStatus
 */
class DisablePersonEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Person
     */
    public $person;

    /**
     * @var mixed
     */
    protected $oldStatus;

    /**
     * Create a new event instance.
     *
     * @param Person $person
     * @param $oldStatus
     */
    public function __construct(Person $person, $oldStatus)
    {
        $this->person = $person;
        $this->oldStatus = $oldStatus;
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
