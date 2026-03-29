<?php

use App\Models\Post;
use App\Models\User;
use Clinically\Companion\Companion;

test('it registers demo-stats as custom feature', function () {
    $customFeatures = Companion::customFeatures();

    expect($customFeatures)->toHaveKey('demo-stats');
});

test('it returns demo stats via custom endpoint', function () {
    User::factory()->create();
    Post::factory(3)->create(['user_id' => User::first()->id]);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/demo-stats');

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'total_users',
            'total_posts',
            'total_comments',
            'total_tags',
        ],
    ]);
    expect($response->json('data.total_posts'))->toBe(3);
});
