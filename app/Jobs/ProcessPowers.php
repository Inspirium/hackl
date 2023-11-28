<?php

namespace App\Jobs;

use App\Models\Club;
use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPowers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->clubPowers();
        $this->globalPowers();
    }

    public function clubPowers()
    {
        $clubs = Club::all();
        foreach ($clubs as $club) {
            $all = $club->teams()->orderBy('rating', 'desc')->get();
            if ($all->count()) {
                $highest = $all->first()->rating;
                $lowest = $all->last()->rating;
                $step = ($highest - $lowest);
                if ($step) {
                    foreach ($all as $user) {
                        $power = ($user->rating - $lowest) / $step * 100;
                        $user->clubs()->updateExistingPivot($club->id, ['power' => $power]);
                    }
                }
            }
        }
    }

    public function globalPowers()
    {
        $all = Team::orderBy('rating', 'desc')->get();
        $highest = $all->first()->rating;
        $lowest = $all->last()->rating;
        $step = ($highest - $lowest);
        if ($step) {
            foreach ($all as $user) {
                $user->power = ($user->rating - $lowest) / $step * 100;
                $user->save();
            }
        }
    }
}
