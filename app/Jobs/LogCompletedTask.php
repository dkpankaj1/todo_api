<?php

namespace App\Jobs;

use App\Models\ToDoTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class LogCompletedTask implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ToDoTask $task,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Task #{$this->task->id} '{$this->task->title}' has been marked as complete.");
    }
}
