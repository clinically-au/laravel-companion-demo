<?php

use Illuminate\Support\Facades\Cache;

test('it returns cache driver info', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/cache/info');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    $response->assertJsonStructure([
        'data' => ['driver', 'store'],
    ]);
});

test('it reads a cache key', function () {
    Cache::put('test-key', 'test-value', 60);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/cache/test-key');

    $response->assertOk();
    $response->assertJsonPath('data.key', 'test-key');
    $response->assertJsonPath('data.value', 'test-value');
});

test('it returns 404 for missing cache key', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/cache/nonexistent-key');

    $response->assertStatus(404);
});

test('it forgets a cache key', function () {
    Cache::put('delete-me', 'value', 60);

    $agentToken = $this->createTestAgent();

    $this->withCompanionAgent($agentToken)
        ->deleteJson('companion/api/cache/delete-me')
        ->assertOk();

    expect(Cache::has('delete-me'))->toBeFalse();
});

test('it requires confirmation for cache flush', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->postJson('companion/api/cache/flush');

    $response->assertStatus(422);
    $this->assertCompanionError($response, 'confirmation_required', 422);
});

test('it enforces prefix allowlist', function () {
    config(['companion.cache.allowed_prefixes' => ['allowed:']]);
    Cache::put('restricted-key', 'value', 60);

    $agentToken = $this->createTestAgent();

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/cache/restricted-key')
        ->assertStatus(403);
});
