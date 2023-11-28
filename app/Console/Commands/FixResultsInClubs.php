<?php

namespace App\Console\Commands;

use App\Models\Result;
use Illuminate\Console\Command;

class FixResultsInClubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:fix_results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add clubs to all results';

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
     * @return int
     */
    public function handle()
    {
        $results = Result::whereNull('club_id')->limit(500)->get();
        foreach ($results as $result) {
            $clubs = [];
            foreach ($result->players as $player) {
                if ($player->id === 2) {
                    $clubs[] = 1;
                } else {
                    $clubs = array_merge($clubs, $player->clubs->map(function ($club) {
                        return $club->id;
                    })->toArray());
                }
            }
            $clubs = array_count_values($clubs);
            arsort($clubs);
            $clubs = array_keys($clubs);
            $club = array_pop($clubs);
            $this->info($result->id.'. '.implode(',', $clubs));
            $result->update(['club_id' => $club]);
        }
    }
}
