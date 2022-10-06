<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSchedulerIsRunning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test:scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the scheduler is running correctly!';

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
        $date = date('d M, Y - h:i A');
        info("[Scheduler Running] - At {$date}");
        $this->info("Scheduler Running at {$date}");
        return 0;
    }
}
