<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $user;
    public $ip;
    public function __construct($ip, $user,$description)
    {
        $this->ip = $ip;
        $this->user = $user;
        $this->description = $description;

        $user->logs()->create([
            'ip_address'=>$ip,
            'description'=>$description
        ]);

        
        $accessible_ids = $user->office->first()->getLowerOfficeIDS();
        session(['accessible_office_ids' => $accessible_ids]);
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // return new PrivateChannel('channel-name');
    }
}
