<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpireOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:tasks';

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
        try {
            Task::where('status', 'pending')
                ->whereNotNull('due_date')
                ->where('due_date', '<', Carbon::now())
                ->update(['status' => 'expired']);
        } catch (\Exception $e) {
            Log::error('Expire -Overdue - Tasks: ' . $e->getMessage());
        }
    }
}
