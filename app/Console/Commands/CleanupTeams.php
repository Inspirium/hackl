<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:cleanup-teams';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $teams = \App\Models\Team::where('number_of_players', 1)
            ->whereHas('primary_contact', function ($query) {
                $query->whereNotNull('deleted_at');
            })
            ->get();
        foreach ($teams as $team) {
            $this->line($team->id);
            $team->delete();
        }
    }
}
