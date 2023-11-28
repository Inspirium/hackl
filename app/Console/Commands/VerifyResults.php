<?php

namespace App\Console\Commands;

use App\Actions\ScoreGame;
use App\Models\Result;
use App\Notifications\ResultVerified;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class VerifyResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:verify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify results older than 48h';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $results = Result::whereNull('verified')->whereNull('non_member')->with('players')->get();
        $now = Carbon::now()->subDays(1);
        /** @var Result $result */
        foreach ($results as $result) {
            if ($result->created_at->lt($now)) {
                if ($result->players->count() < 2 && !$result->game_id) {
                    $result->delete();
                    continue;
                }
                $this->line($result->id);
                $result->updateELO();
                $result->players()->updateExistingPivot($result->players[1]->id, ['verified' => true]);
                if ($result->game) {
                    // ScoreGame::score($result->game);
                }
                $players = collect([]);
                foreach ($result->players as $team) {
                    $players = $players->merge($team->players);
                    foreach ($team->players as $p) {
                        $p->notify((new ResultVerified($result))->locale($p->lang));
                    }
                }

                    //Notification::send($players, new ResultVerified($result));
            }
        }
    }
}
