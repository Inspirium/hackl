<?php

namespace App\Events;

use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveResultUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Game $game;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
        $this->game->load(['result']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('live.'.$this->game->id);
    }

    public function broadcastWith()
    {
        return ['game' => GameResource::make($this->game)];
    }
}
