<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        $users = User::factory(5)->create();
        $allUsers = $users->push($admin);

        $tags = Tag::factory(8)->create();

        Post::factory(12)
            ->published()
            ->recycle($allUsers)
            ->create()
            ->each(function (Post $post) use ($allUsers, $tags) {
                Comment::factory(rand(1, 4))
                    ->recycle($allUsers)
                    ->approved()
                    ->create(['post_id' => $post->id]);

                $post->tags()->attach($tags->random(rand(1, 3)));
            });

        Post::factory(8)
            ->draft()
            ->recycle($allUsers)
            ->create()
            ->each(function (Post $post) use ($tags) {
                $post->tags()->attach($tags->random(rand(1, 2)));
            });
    }
}
