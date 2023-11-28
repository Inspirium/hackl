<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanClubs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean:clubs';

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
        $teams = DB::table('club_team')->groupBy('club_id', 'team_id')->havingRaw('count(*) > 1')->get();
        foreach ($teams as $team) {
            $this->line($team->club_id . ' ' . $team->team_id);
            $rows = DB::table('club_team')->where('club_id', $team->club_id)
                ->where('team_id', $team->team_id)
                ->orderBy('id', 'asc')
                ->get();
            $i = 0;
            foreach ($rows as $row) {
                if ($i) {
                    DB::table('club_team')->where('id', $row->id)->delete();
                }
                $i++;
            }
        }
        $users = DB::table('club_user')->groupBy('club_id', 'player_id')->havingRaw('count(*) > 1')->get();
        foreach ($users as $user) {
            $rows = DB::table('club_user')->where('club_id', $user->club_id)
                ->where('player_id', $user->player_id)
                ->orderBy('id', 'asc')
                ->get();
            $i = 0;
            foreach ($rows as $row) {
                if ($i) {
                    DB::table('club_user')->where('id', $row->id)->delete();
                }
                $i++;
            }
        }

        return Command::SUCCESS;
    }
}
