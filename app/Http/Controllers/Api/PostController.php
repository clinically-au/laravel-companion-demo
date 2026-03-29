<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->published()
            ->with('tags')
            ->latest('published_at')
            ->paginate(15);

        return response()->json($posts);
    }

    public function show(Post $post): JsonResponse
    {
        $post->load(['user', 'tags', 'comments.user']);

        return response()->json($post);
    }
}
