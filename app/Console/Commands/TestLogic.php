<?php

namespace App\Console\Commands;

use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\DNS;
use Illuminate\Console\Command;

class TestLogic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:cf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $key = new APIKey(env('CLOUDFLARE_USER'), env('CLOUDFLARE_API_KEY'));
        $adapter = new Guzzle($key);
        $dns = new DNS($adapter);
        $t = $dns->addRecord('0e86b785e3437e48006fd60363e47a4c', 'A', 'marko.tenis.plus', '85.25.211.227', 1, true);
        var_dump($t);
    }
}
