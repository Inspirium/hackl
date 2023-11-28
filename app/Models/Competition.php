<?php

namespace App\Models;

use App\Models\CompetitionScoring\AggregateLeagues;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function teams() {
        return $this->belongsToMany(Team::class, 'competition_team')->withPivot(['points', 'played', 'won', 'drawn', 'lost']);
    }

    public function score() {
        $scoring = new AggregateLeagues();
        $leagues = League::query()->where('competition_id', $this->id)->get();
        foreach ($leagues as $league) {
            dump('league: ' . $league->id);
            foreach ($league->groups as $group) {
                dump('league group: ' . $group->id);
                $level = intval($group->name);
                foreach ($group->games as $game) {
                    $scoring->handle($game, $this, $level);
                }
            }
        }
    }
}
