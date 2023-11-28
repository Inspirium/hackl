<?php

namespace App\Console\Commands;

use App\Models\Result;
use Illuminate\Console\Command;

class DeleteResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:results';

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
        $results = Result::query()->whereNull('non_member')->where('id', '>', 6000)->has('players','=', 1)->orderBy('id','desc')->get();
        foreach ($results as $result) {
            $result->delete();
        }
        $this->line($results->count());
    }
}
