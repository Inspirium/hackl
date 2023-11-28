<?php

namespace App\Console\Commands;

use App\Models\Team;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateTeams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenis:migrate:teams';

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

        $users = User::query()->whereDoesntHave('teams', function ($query) {
            $query->where('number_of_players', 1);
        })->get();
        foreach ($users as $user) {
            $team = Team::create(['display_name' => $user->display_name, 'image' => $user->image, 'number_of_players' => 1, 'primary_contact_id' => $user->id]);
            foreach ($user->clubs as $club) {
                $team->clubs()->attach($club->id);
            }
            $team->players()->attach($user->id);
            $this->line($user->id);
        }
        /*$teams = Team::query()->where('number_of_players', 1)->whereDoesntHave('players')->get();
        foreach ($teams as $team) {
            $this->line($team->id . ' ' .$team->primary_contact_id);
            $team->players()->attach($team->primary_contact_id);
            $team->save();
        }*/
/*
        $teams = Team::query()->with('primary_contact')->whereDoesntHave('players')->where('number_of_players', 1)->get();
        foreach ($teams as $team) {
            $this->line($team->id);
            $team->players()->attach($team->primary_contact);
        }*/
    }
}
