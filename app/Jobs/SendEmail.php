<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $recipient;
    public $massage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipient, $massage)
    {
        $this->recipient = $recipient;
        $this->massage = $massage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        echo "Send massage too" . "||" . $this->recipient . PHP_EOL;
        echo "Massage" . "||" . $this->massage . PHP_EOL;
    }
}
