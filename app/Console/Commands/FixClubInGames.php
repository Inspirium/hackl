<?php

namespace App\Console\Commands;

use App\Models\League\Group;
use App\Models\Result;
use Illuminate\Console\Command;

class FixClubInGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-club-in-games';

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
        $results = Result::whereHas('game', function ($query) {
            $query->where('type_type', Group::class);
        })->orderBy('id', 'desc')->limit('100')->get();
        foreach ($results as $result) {
            if (!$result->game->type->league) {
                continue;
            }
            if ($result->club_id != $result->game->type->league->club_id) {
                $this->line('Fixing result ' . $result->id);
                $result->club_id = $result->game->type->league->club_id;
                $result->save();
            }

        }
    }
}
