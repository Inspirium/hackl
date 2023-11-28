<?php

namespace App\Console\Commands;

use App\Models\Club;
use App\Models\User;
use Illuminate\Console\Command;

class CalculatePowers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:powers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $this->clubPowers();
        $this->globalPowers();
    }

    public function clubPowers()
    {
        $clubs = Club::all();
        foreach ($clubs as $club) {
            $all = $club->players()->orderBy('rating', 'desc')->get();
            if ($all->count()) {
                $highest = $all->first()->rating;
                $lowest = $all->last()->rating;
                $step = ($highest - $lowest);
                foreach ($all as $user) {
                    $power = ($user->rating - $lowest) / $step * 100;
                    $this->line($user->id.' '.$power);
                    $user->clubs()->updateExistingPivot($club->id, ['power' => $power]);
                }
            }
        }
    }

    public function globalPowers()
    {
        $all = User::orderBy('rating', 'desc')->get();
        $highest = $all->first()->rating;
        $lowest = $all->last()->rating;
        $step = ($highest - $lowest);
        foreach ($all as $user) {
            $user->power_global = ($user->rating - $lowest) / $step * 100;
            $user->save();
        }
    }
}
