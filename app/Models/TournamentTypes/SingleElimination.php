<?php

namespace App\Models\TournamentTypes;

use App\Actions\CreateGame;
use App\Actions\SaveImageAction;
use App\Models\Game;
use App\Models\Tournament;
use App\Models\TournamentRound;
use Illuminate\Http\Request;

class SingleElimination
{
    public function validate(Request $request): array
    {
        return $request->validate([
            'name' => 'required_without:id',
            'access_level' => 'required_without:id|in:open,private',
            'number_of_players' => 'required_without:id|integer|in:0,4,8,16,32,64,128,256',
            'playing_sets' => 'required_without:id|integer|in:1,2,3',
            'game_in_set' => 'required_without:id|integer|in:4,6,8,10',
            'last_set' => 'required_without:id|in:classic,advantage,tie7,tie10',
            'application_deadline' => 'nullable',
            'active_to' => 'nullable',
            'active_from' => 'nullable',
            'description' => 'nullable',
            'price' => 'nullable',
            'status' => 'nullable',
            'boarding_type' => 'nullable',
            'winner.new_image' => 'sometimes|nullable|image64:jpeg,jpg,png',
            'winner.description' => 'sometimes|nullable',
            'is_doubles' => 'nullable',
            'cup_scoring' => 'sometimes|nullable',
            'show_on_tenisplus' => 'sometimes',
            'show_in_club' => 'sometimes|boolean',
        ]);
    }

    public function prepareData(array $validated, $model = null): array
    {
        if (isset($validated['winner']['new_image']) && $validated['winner']['new_image']) {
            $c = new SaveImageAction();
            $validated['winner']['image'] = $c->execute($validated['winner']['new_image'], 'news');
        }
        $out = [];
        $keys = [
            'name', 'description', 'number_of_players', 'active_to', 'active_from', 'application_deadline', 'status',
            'access_level', 'show_on_tenisplus', 'show_in_club',
        ];
        foreach ($keys as $key) {
            if (isset($validated[$key])) {
                $out[$key] = $validated[$key];
            }
        }
        $data_keys = ['playing_sets', 'game_in_set', 'last_set', 'price', 'boarding_type', 'is_doubles', 'cup_scoring'];
        if ($model) {
            $out['data'] = $model->data;
        }
        foreach ($data_keys as $key) {
            if (isset($validated[$key])) {
                $out['data'][$key] = $validated[$key];
            }
        }
        if (isset($validated['winner']['image'])) {
            $out['data']['winner']['image'] = $validated['winner']['image'];
        }
        if (isset($validated['winner']['description'])) {
            $out['data']['winner']['description'] = $validated['winner']['description'];
        }
        $out['type'] = 'SingleElimination';
        if (isset($out[0])) {
            unset($out[0]);
        }

        return $out;
    }

    public function save(array $data)
    {
        // TODO: Implement save() method.
    }

    public function createGames(Tournament $tournament)
    {
        // clear old games
        foreach ($tournament->rounds as $round) {
            foreach ($round->games as $game) {
                $game->delete();
            }
            $round->delete();
        }
        $no = $tournament->number_of_players;
        if (! $no) {
            $actual = $tournament->players->count();
            $no = 1;
            while ($no < $actual) {
                $no = $no * 2;
            }
        }
        // create rounds
        $n = log($no, 2);
        for ($r = 1; $r <= $n; $r++) {
            $noGames = $no / (2 ** $r);
            $round = TournamentRound::create([
                'order' => $r,
                'tournament_id' => $tournament->id,
                'marker' => $r,
            ]);
            for ($i = 1; $i <= $noGames; $i++) {
                CreateGame::create($round, [], null, $i);
            }
        }
    }

    public function fillGames(Tournament $tournament)
    {
        $seed = $tournament->players()->whereNotNull('seed')->orderBy('seed', 'asc')->get();
        $other = $tournament->players()->whereNull('seed')->inRandomOrder()->get();
        $out = [];
        $i = 1;
        foreach ($seed as $player) {
            $out[$i] = $player;
            $i++;
        }
        foreach ($other as $player) {
            $out[$i] = $player;
            $i++;
        }
        $out = array_splice($out, 0, $tournament->number_of_players);
        $bracket = $this->getBracket(count($out));
        /** @var TournamentRound $round */
        $round = $tournament->rounds()->where('order', 1)->first();
        foreach ($bracket as $key => $values) {
            /** @var Game $game */
            $game = $round->games()->where('order', ($key + 1))->first();
            if ($game) {
                if ($values[0]) {
                    $game->players()->attach($out[$values[0]-1], ['order' => 0]);
                }
                if ($values[1]) {
                    $game->players()->attach($out[$values[1]-1], ['order' => 1]);
                }
            }
        }
    }

    public function getBracket($participantsCount)
    {
        $rounds = ceil(log($participantsCount) / log(2));

        if ($participantsCount < 2) {
            return [];
        }

        $matches = [[1, 2]];

        for ($round = 1; $round < $rounds; $round++) {
            $roundMatches = [];
            $sum = pow(2, $round + 1) + 1;
            foreach ($matches as $match) {
                $home = $this->changeIntoBye($match[0], $participantsCount);
                $away = $this->changeIntoBye($sum - $match[0], $participantsCount);
                $roundMatches[] = [$home, $away];
                $home = $this->changeIntoBye($sum - $match[1], $participantsCount);
                $away = $this->changeIntoBye($match[1], $participantsCount);
                $roundMatches[] = [$home, $away];
            }
            $matches = $roundMatches;
        }

        return $matches;
    }

    private function changeIntoBye($seed, $participantsCount)
    {
        return $seed <= $participantsCount ? $seed : null;
    }
}
