<?php

namespace App\Console\Commands;

use App\Actions\CreateLeagueGames;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigratePlayersInObjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:games';

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
        $gameP = DB::table('result_participant')->where('participant_type', User::class)->get();
        foreach ($gameP as $par) {
            $team = Team::query()->where('primary_contact_id', $par->participant_id)->where('number_of_players', 1)->first();
           if ($team) {
               DB::table('result_participant')->where('id', $par->id)->update([
                   'participant_id' => $team->id,
                    'participant_type' => Team::class
               ]);
           }
        }
    }
}
