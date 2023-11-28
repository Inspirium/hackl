<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AssignPrimaryClub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:primaryClub';

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
        $users = User::query()->whereNull('primary_club_id')->get();
        foreach ($users as $user) {
            if ($user->clubs[0]) {
                $user->primary_club_id = $user->clubs[0]->id;
                $user->save();
            }
        }
    }
}
