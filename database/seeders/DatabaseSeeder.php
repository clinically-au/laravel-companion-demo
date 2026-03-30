<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Comment::query()->delete();
        Post::withTrashed()->forceDelete();
        Tag::query()->delete();

        $admin = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => 'password'],
        );

        $userNames = ['Alice Johnson', 'Bob Smith', 'Carol Davis', 'Dan Wilson', 'Eve Martinez'];
        $users = collect($userNames)->map(fn (string $name) => User::firstOrCreate(
            ['email' => Str::slug($name).'@example.com'],
            ['name' => $name, 'password' => 'password', 'email_verified_at' => now()],
        ));
        $allUsers = $users->push($admin);

        $tagNames = ['Laravel', 'PHP', 'Livewire', 'Tailwind', 'Testing', 'API', 'Security', 'Performance'];
        $tags = collect($tagNames)->map(fn (string $name) => Tag::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name],
        ));

        $publishedPosts = [
            'Getting Started with Laravel Companion',
            'Building RESTful APIs in Laravel',
            'Livewire 4: What You Need to Know',
            'Mastering Eloquent Relationships',
            'Laravel Testing Best Practices',
            'Authentication with Fortify',
            'Queue Workers and Job Batching',
            'Caching Strategies for Laravel Apps',
            'Route Model Binding Deep Dive',
            'Laravel Middleware Explained',
            'Database Migrations Done Right',
            'Event-Driven Architecture in Laravel',
        ];

        $draftPosts = [
            'Upcoming Features in Laravel 13',
            'Advanced Validation Techniques',
            'Custom Artisan Commands Guide',
            'Service Container Internals',
            'Deploying Laravel with Forge',
            'Real-Time Notifications with SSE',
            'Laravel Policies and Gates',
            'Performance Profiling Tips',
        ];

        $commentBodies = [
            'Great article, very helpful!',
            'Thanks for sharing this.',
            'I learned something new today.',
            'This saved me hours of debugging.',
            'Could you elaborate on this topic?',
            'Well written and easy to follow.',
            'Bookmarked for future reference.',
            'Exactly what I was looking for.',
        ];

        $baseDate = Carbon::now()->subYear();

        foreach ($publishedPosts as $i => $title) {
            $post = Post::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'user_id' => $allUsers[$i % $allUsers->count()]->id,
                    'title' => $title,
                    'body' => "This is the body content for: {$title}. It covers important concepts and practical examples for Laravel developers.",
                    'published' => true,
                    'published_at' => $baseDate->copy()->addDays($i * 30),
                ],
            );

            $commentCount = ($i % 4) + 1;
            for ($c = 0; $c < $commentCount; $c++) {
                Comment::firstOrCreate(
                    ['post_id' => $post->id, 'body' => $commentBodies[($i + $c) % count($commentBodies)]],
                    [
                        'user_id' => $allUsers[($i + $c + 1) % $allUsers->count()]->id,
                        'approved' => true,
                    ],
                );
            }

            $post->tags()->syncWithoutDetaching(
                $tags->pluck('id')->shuffle()->take(($i % 3) + 1),
            );
        }

        foreach ($draftPosts as $i => $title) {
            $post = Post::firstOrCreate(
                ['slug' => Str::slug($title)],
                [
                    'user_id' => $allUsers[$i % $allUsers->count()]->id,
                    'title' => $title,
                    'body' => "Draft content for: {$title}. This post is still being worked on.",
                    'published' => false,
                    'published_at' => null,
                ],
            );

            $post->tags()->syncWithoutDetaching(
                $tags->pluck('id')->shuffle()->take(($i % 2) + 1),
            );
        }
    }
}
