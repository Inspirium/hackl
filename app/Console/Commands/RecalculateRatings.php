<?php

namespace App\Console\Commands;

use App\Models\Result;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecalculateRatings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:recalculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate ratings';

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
        //set all users to initial rating
        DB::table('teams')->update(['rating' => 1500]);
        DB::table('club_team')->update(['rating' => 1500]);
        //iterate through verified results and calculate ELO
        $results = Result::where('verified', 1)->orderBy('created_at', 'asc')->get();
        foreach ($results as $result) {
            $this->line($result->id);
            $result->updateELO();
        }
    }
}
