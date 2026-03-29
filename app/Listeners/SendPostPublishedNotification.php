<?php

namespace App\Listeners;

use App\Events\PostPublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendPostPublishedNotification implements ShouldQueue
{
    public function handle(PostPublished $event): void
    {
        Log::info("Sending notification for published post: {$event->post->title}");
    }
}
