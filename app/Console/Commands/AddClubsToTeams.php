<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;

class AddClubsToTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clubs:teams';

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
        $users = User::all();
        /** @var User $user */
        foreach ($users as $user) {
            $user_clubs = $user->clubs()->wherePivot('status', 'member')->pluck('clubs.id');
            /** @var Team $team */
            if ($user->teams) {
                foreach ($user->teams as $team) {
                    $team_clubs = $team->clubs()->pluck('clubs.id');
                    $diff = $user_clubs->diff($team_clubs);
                    if ($diff->count()) {
                        $this->line( $user->id . ' ' .$diff->join(','));
                        $team->clubs()->syncWithoutDetaching($diff);
                    }
                }
            } else {
                // create team and assign to club
                $this->line('user has no team ' . $user->id);
            }
        }
    }
}
