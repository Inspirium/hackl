<?php

namespace App\Console\Commands;

use App\Models\League;
use App\Models\Thread;
use Illuminate\Console\Command;

class LeagueThreads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:league-threads {league}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $league = $this->argument('league');
        $league = League::query()->findOrFail($league);
        $this->info('League: ' . $league->name);
        if (!$league->thread) {
            $t = new Thread([
                'title' => __('titles.new_league_thread_title', ['name' => $league->name]),
                'club_id' => $league->club_id,
            ]);
            $t->threadable()->associate($league);
            $t->save();
            $t->players()->attach($league->players);
        }
        foreach ($league->groups as $group) {
            if (!$group->thread) {
                if ($league->classification == 'pyramid') {
                    $t = new Thread([
                        'title' => __('titles.new_league_group_thread_title', ['name' => $league->name . ' - ' . $group->name]),
                        'club_id' => $league->club_id,
                    ]);
                    $t->threadable()->associate($group);
                    $t->save();
                    $t->players()->attach($group->players);
                }
            }
        }
    }
}
