<?php

namespace App\Console\Commands;

use App\Models\Competition;
use Illuminate\Console\Command;

class ScoreCompetition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:score-competition';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $competitions = Competition::query()->where('active', 1)->get();
        foreach ($competitions as $competition) {
            $competition->teams()->detach();
            $competition->score();
        }
    }
}
