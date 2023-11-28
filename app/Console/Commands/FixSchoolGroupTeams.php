<?php

namespace App\Console\Commands;

use App\Models\SchoolGroup;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixSchoolGroupTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:fix:schools';

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
        $rows = DB::table('school_group_user')->get();
        foreach ($rows as $row) {
            $team = DB::table('teams')->where('number_of_players', 1)->where('primary_contact_id', $row->user_id)->first();
            if ($team) {
                DB::table('school_group_user')->where(['id' => $row->id])->update(['team_id' => $team->id]);
            }
        }
    }
}
