<?php

namespace App\Console\Commands;

use App\Models\Result;
use Illuminate\Console\Command;

class UnverifyResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:unverify {result}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unverify result';

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
        $resultId = $this->argument('result');
        $result = Result::find($resultId);
        $score = $result->points;
        $winner = $result->winner;
        foreach ($result->players as $index => $player) {
            if ($index == $winner) {
                $player->rating = $player->rating - $score;
            } else {
                $player->rating = $player->rating + $score;
            }
            $player->save();
        }
        $result->verified = false;
        $result->points = 0;
        $result->save();
    }
}
