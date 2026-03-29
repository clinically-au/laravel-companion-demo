<?php

namespace App\Listeners;

use App\Events\PostPublished;
use Illuminate\Support\Facades\Log;

class UpdateSearchIndex
{
    public function handle(PostPublished $event): void
    {
        Log::info("Updating search index for post: {$event->post->title}");
    }
}
