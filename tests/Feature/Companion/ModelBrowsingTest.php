<?php

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;

test('it lists model records with pagination', function () {
    $user = User::factory()->create();
    Post::factory(3)->published()->create(['user_id' => $user->id]);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post/records');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    $response->assertJsonStructure([
        'data',
        'meta' => ['pagination'],
    ]);
    expect($response->json('meta.pagination.total'))->toBe(3);
});

test('it returns CompanionSerializable format for Post', function () {
    $user = User::factory()->create();
    Post::factory()->published()->create(['user_id' => $user->id]);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post/records');

    $record = $response->json('data.0');
    expect($record)->toHaveKeys(['id', 'title', 'slug', 'published', 'published_at', 'created_at']);
    expect($record)->not->toHaveKey('body');
});

test('it applies scope on CompanionSerializable model', function () {
    $user = User::factory()->create();
    Post::factory(2)->published()->create(['user_id' => $user->id]);
    Post::factory(3)->draft()->create(['user_id' => $user->id]);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post/records?scope=published');

    $response->assertOk();
    expect($response->json('meta.pagination.total'))->toBe(2);
});

test('it filters records by column', function () {
    $user = User::factory()->create();
    Post::factory()->published()->create(['user_id' => $user->id, 'title' => 'Unique Test Title']);
    Post::factory()->published()->create(['user_id' => $user->id, 'title' => 'Another Post']);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post/records?filter[title]=Unique Test Title');

    $response->assertOk();
    expect($response->json('meta.pagination.total'))->toBe(1);
});

test('it sorts records', function () {
    $user = User::factory()->create();
    Post::factory()->published()->create(['user_id' => $user->id, 'title' => 'Alpha Post']);
    Post::factory()->published()->create(['user_id' => $user->id, 'title' => 'Zeta Post']);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post/records?sort=title&direction=desc');

    $response->assertOk();
    expect($response->json('data.0.title'))->toBe('Zeta Post');
});

test('it searches records', function () {
    $user = User::factory()->create();
    Post::factory()->published()->create(['user_id' => $user->id, 'title' => 'Laravel Guide']);
    Post::factory()->published()->create(['user_id' => $user->id, 'title' => 'PHP Tips']);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post/records?search=Laravel');

    $response->assertOk();
    expect($response->json('meta.pagination.total'))->toBe(1);
});

test('it shows single record with relationships', function () {
    $user = User::factory()->create();
    $post = Post::factory()->published()->create(['user_id' => $user->id]);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson("companion/api/models/Post/records/{$post->id}");

    $response->assertOk();
    $this->assertCompanionResponse($response);
});

test('it hides sensitive columns from User records', function () {
    User::factory()->create();

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/User/records');

    $response->assertOk();
    $record = $response->json('data.0');
    expect($record)->not->toHaveKey('password');
    expect($record)->not->toHaveKey('remember_token');
});
