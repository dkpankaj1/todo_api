<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LogSchedulerRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:log-run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log a message indicating the scheduler is running';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Scheduler: running every minute');
    }
}
