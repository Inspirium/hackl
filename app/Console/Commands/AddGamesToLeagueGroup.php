<?php

namespace App\Console\Commands;

use App\Actions\CreateLeagueGames;
use App\Models\League;
use Illuminate\Console\Command;

class AddGamesToLeagueGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:add_games';

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
    public function handle(CreateLeagueGames $createLeagueGames)
    {
        $league = League::find(769);
        $createLeagueGames->handle($league);
    }
}
