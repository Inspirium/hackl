<?php

namespace App\Events;

use App\Http\Resources\GameResource;
use App\Models\Game;
use App\Models\League;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeagueUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private League $league;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(League $league)
    {
        $this->league = $league;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('league.'.$this->league->id);
    }

    public function broadcastWith()
    {
        return [];
    }
}
