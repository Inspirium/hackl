<?php

namespace App\Console\Commands;

use App\Models\Club;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateRanks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:ranks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Ranks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->club();
        $this->global();
    }

    private function club()
    {
        $clubs = Club::all();
        /** @var Club $club */
        foreach ($clubs as $club) {
            for ($i = 1; $i < 3; $i++) {
                $all = $club->teams()->where('number_of_players', $i)->orderByPivot('rating', 'desc')->get();
                $current_rating = 10000;
                $last_rank = 0;
                $actual_rank = 1;
                /** @var Team $user */
                foreach ($all as $user) {
                    if ($user->results()
                        ->whereNull('non_member')
                        ->where('results.verified', 1)
                        ->where('club_id', $club->id)
                        ->whereDate('results.date', '>', Carbon::now()->subMonth(6)->toDateString())
                        ->count()) {
                        $ur = $user->getRatingClubAttribute($club->id);
                        if ($ur < $current_rating) {
                            $last_rank = $actual_rank;
                        }
                        $user->clubs()->updateExistingPivot($club->id, ['rank' => $last_rank]);
                        $current_rating = $ur;
                        $actual_rank++;
                    } else {
                        $user->clubs()->updateExistingPivot($club->id, ['rank' => 0]);
                    }
                }
            }
        }
    }

    private function global()
    {
        for ($i = 1; $i < 3; $i++) {
            $all = Team::orderBy('rating', 'desc')
                ->where('number_of_players', $i)
                ->get();
            $current_rating = 10000;
            $last_rank = 0;
            $actual_rank = 1;
            foreach ($all as $user) {
                if ($user->results()->whereNull('non_member')->where('results.verified', 1)->whereDate('results.date', '>', Carbon::now()->subMonth(6)->toDateString())->count()) {
                    if ($user->rating < $current_rating) {
                        $last_rank = $actual_rank;
                    }
                    $user->rank = $last_rank;
                    $user->save();
                    $current_rating = $user->rating;
                    $actual_rank++;
                } else {
                    $user->rank = 0;
                    $user->save();
                }
            }
        }
    }
}
