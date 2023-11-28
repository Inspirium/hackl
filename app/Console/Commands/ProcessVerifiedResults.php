<?php

namespace App\Console\Commands;

use App\Models\Result;
use Illuminate\Console\Command;

class ProcessVerifiedResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:updateElo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update ELO for verified results';

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
        $results = Result::whereNull('verified')->whereNotNull('verified_at')->whereNull('non_member')->with('players')->get();
        /** @var Result $result */
        foreach ($results as $result) {
            $result->updateELO();
        }
    }
}
