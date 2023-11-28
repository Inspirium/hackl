<?php

namespace App\Console\Commands;

use App\Models\Thread;
use App\Models\User;
use App\Notifications\NewMessage;
use App\Notifications\TestNotification;
use Illuminate\Console\Command;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tennis:test';

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
     * @return mixed
     */
    public function handle()
    {
        $thread = Thread::find(1);
        $user1 = User::find(813);
        $user = User::find(1);
        $user->notify(new TestNotification($user));
    }
}
