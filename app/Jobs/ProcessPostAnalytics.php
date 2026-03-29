<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessPostAnalytics implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $postId,
    ) {}

    public function handle(): void
    {
        Log::info("Processing analytics for post #{$this->postId}");
    }
}
