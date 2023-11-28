<?php

namespace App\Actions;

use App\Models\Game;
use App\Models\League\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use RectorPrefix202304\Symfony\Component\Console\Output\ConsoleOutput;

class ProcessStats
{

    private function dump($value) {
        if (app()->runningInConsole()) {
            $output = new ConsoleOutput();
            $output->writeln("<info>$value</info>");
        }
    }

    /**
     * Execute the job.
     */
    public function handle(Group $group): void
    {
        $out = [];
        foreach ($group->players as $player) {
            $a = [
                'name' => $player->display_name,
                'id' => $player->id,
                'matches' => [
                    'wins' => 0,
                    'losses' => 0,
                    'total' => 0,
                ],
                'sets' => [
                    'wins' => 0,
                    'losses' => 0,
                ],
                'points' => [
                    'wins' => 0,
                    'losses' => 0,
                ],
                'score' => $player->pivot->score,
            ];
            $games = Game::where('type_id', $group->id)->where('type_type', Group::class)->whereHas('players', function ($q) use ($player) {
                $q->where('participant_id', $player->id);
            })->get();
            foreach ($games as $game) {
                if (in_array($game->is_surrendered, [1, 2])) {
                    $a['matches']['total']++;
                    if ($game->players[0]->id == $player->id) {
                        $p = 2;
                    } else {
                        $p = 1;
                    }
                    if ($game->is_surrendered == $p) {
                        $a['matches']['wins']++;
                    } else {
                        $a['matches']['losses']++;
                    }
                    continue;
                }
                $result = $game->result;
                if ($result) {
                    if ($result->players[0]->id == $player->id) {
                        $p = 0;
                    } else {
                        $p = 1;
                    }
                    if ($result->winner === $p) {
                        $a['matches']['wins']++;
                    } else {
                        $a['matches']['losses']++;
                    }
                    $a['matches']['total']++;

                    foreach ($result->sets as $key => $set) {
                        if ($key === 'tie_break') {
                            continue;
                        }
                        if ($set[0] > $set[1]) {
                            if ($p === 0) {
                                $a['sets']['wins']++;
                            } else {
                                $a['sets']['losses']++;
                            }
                        }
                        if ($set[0] < $set[1]) {
                            if ($p === 1) {
                                $a['sets']['wins']++;
                            } else {
                                $a['sets']['losses']++;
                            }
                        }
                        $a['points']['wins'] += $set[$p];
                        $a['points']['losses'] += $set[!$p];
                    }
                }
            }
            $out[$player->id] = $a;

            // update in db
            $group->players()->updateExistingPivot($player->id, [
                    'set_diff' => ($a['sets']['wins'] - $a['sets']['losses']),
                    'point_diff' => ($a['points']['wins'] - $a['points']['losses'])]
            );
        }
            \Cache::put('stats_group'.$group->id, $out, 24 * 3600);
        $i = 1;
        foreach ($out as $id => $value) {
            $group->players()->updateExistingPivot($id, ['position' => $i]);
            $i++;
        }
        uasort($out, function($a, $b) use ($group) {
            $this->dump('Igrači: ' . $a['name'] . ' - ' . $b['name']);
            $this->dump('Bodovi: '.$a['score'] . ' - ' . $b['score']);
            $this->dump('Setovi: '. $a['sets']['wins'] - $a['sets']['losses'] . ' - ' . $b['sets']['wins'] - $b['sets']['losses']);
            $this->dump('Gemovi: '. $a['points']['wins'] - $a['points']['losses'] . ' - ' . $b['points']['wins'] - $b['points']['losses']);

           if ($a['score'] == $b['score']) {
               // check matchups
               $pa = 0;
               $pb = 0;

               $games = Game::where('type_id', $group->id)->where('type_type', Group::class)
                   ->whereHas('players', function ($q) use ($a) {
                    $q->where('participant_id', $a['id']);
                    })
                   ->whereHas('players', function ($q) use ($b) {
                       $q->where('participant_id', $b['id']);
                   })
                   ->get();

               foreach ($games as $game) {
                   // var_dump('game ' . $game->id);
                     if (in_array($game->is_surrendered, [1, 2])) {
                          if ($game->players[0]->id == $a['id']) {
                            $p = 2;
                          } else {
                            $p = 1;
                          }
                          if ($game->is_surrendered == $p) {
                              $pb++;
                          } else {
                              $pa++;
                          }
                          continue;
                     }
                     $result = $game->result;
                     if ($result) {
                          if ($result->players[0]->id == $a['id']) {
                            $p = 0;
                          } else {
                            $p = 1;
                          }
                          if ($result->winner === $p) {
                            $pa++;
                          } else {
                            $pb++;
                          }
                     }
               }
               $this->dump('Ogledi: '. $pa . ' - ' . $pb);
               if ($pa == $pb) {
                   if ($a['sets']['wins'] == $b['sets']['wins']) {
                       // check points
                       $this->dump('odlučuju gemovi: ' . ($b['points']['wins'] - $b['points']['losses'] ) - ($a['points']['wins'] - $a['points']['losses']) );
                          return ($b['points']['wins'] - $b['points']['losses'] ) - ($a['points']['wins'] - $a['points']['losses']);
                   }
                   $this->dump('odlučuju setovi: ' . ($b['sets']['wins'] - $b['sets']['losses']) - ($a['sets']['wins'] - $a['sets']['losses']));
                   return ($b['sets']['wins'] - $b['sets']['losses']) - ($a['sets']['wins'] - $a['sets']['losses']);
               }
               $this->dump('odlučuju ogledi: ' . $pb - $pa);
               return $pb - $pa;
           }
            $this->dump('odlučuju bodovi: ' . $b['score'] - $a['score']);
           return $b['score'] - $a['score'];
        });
        $i = 1;
        foreach ($out as $id => $value) {
            $this->dump($value['name'] . ' - ' . $i);
            $group->players()->updateExistingPivot($id, ['position' => $i]);
            $i++;
        }


    }
}
