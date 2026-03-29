<?php

test('it discovers all app models', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models');

    $response->assertOk();
    $this->assertCompanionResponse($response);

    $models = collect($response->json('data'));
    $shortNames = $models->pluck('short_name')->toArray();

    expect($shortNames)->toContain('User', 'Post', 'Comment', 'Tag');
});

test('it detects soft deletes on Post model', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models');

    $post = collect($response->json('data'))
        ->firstWhere('short_name', 'Post');

    expect($post['soft_deletes'])->toBeTrue();
});

test('it returns model metadata', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post');

    $response->assertOk();
    $this->assertCompanionResponse($response);
});

test('it returns model relationships', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/Post/relationships');

    $response->assertOk();
    $this->assertCompanionResponse($response);
});

test('it returns 404 for unknown model', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/models/NonExistent');

    $response->assertStatus(404);
});
