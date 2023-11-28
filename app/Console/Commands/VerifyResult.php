<?php

namespace App\Console\Commands;

use App\Models\Result;
use App\Notifications\ResultVerified;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class VerifyResult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:verify_one {result}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify result';

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
        $result->updateELO();
        $result->players()->updateExistingPivot($result->players[1]->id, ['verified' => true]);
        //Notification::send($result->players, new ResultVerified($result));
    }
}
