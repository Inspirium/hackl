<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Notifications\NewApplicant;
use App\Notifications\NewMember;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class FixTeamClubMembership extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:fix-team-clubs';

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
        $teams = Team::query()->where('number_of_players', 1)
            ->whereDoesntHave('clubs')
            ->with('primary_contact.clubs')
            ->get();
        foreach ($teams as $team) {
            $this->line($team->id);
            if ($team->primary_contact) {
                $clubs = $team->primary_contact->clubs;
                foreach ($clubs as $club) {
                    $team->clubs()->attach($club->id);
                }
            }
        }
    }
}
