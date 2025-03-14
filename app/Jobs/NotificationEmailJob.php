<?php

namespace App\Jobs;

use App\Mail\NotificationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class NotificationEmailJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */

     protected $user;
    protected $task;
    public function __construct($user, $task)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new NotificationEmail($this->user, $this->task));
    }
}
