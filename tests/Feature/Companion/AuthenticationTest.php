<?php

use Clinically\Companion\Models\CompanionAgent;

test('it returns 401 without bearer token', function () {
    $this->getJson('companion/api/ping')
        ->assertStatus(401);
});

test('it returns 401 with invalid token', function () {
    $this->withHeader('Authorization', 'Bearer invalid-token')
        ->getJson('companion/api/ping')
        ->assertStatus(401);
});

test('it returns 401 with revoked token', function () {
    $agentToken = $this->createTestAgent();
    $agentToken->agent->revoke();

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertStatus(401);
});

test('it returns 401 with expired token', function () {
    $agentToken = $this->createTestAgent();
    $agentToken->agent->update(['expires_at' => now()->subDay()]);

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertStatus(401);
});

test('it returns 403 when IP not in allowlist', function () {
    $agentToken = $this->createTestAgent();
    $agentToken->agent->update(['ip_allowlist' => ['192.168.1.1']]);

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertStatus(403);
});

test('it authenticates successfully with valid token', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    $response->assertJsonPath('data.status', 'ok');
});

test('it updates last_seen_at on authentication', function () {
    $agentToken = $this->createTestAgent();

    expect($agentToken->agent->last_seen_at)->toBeNull();

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertOk();

    $agentToken->agent->refresh();
    expect($agentToken->agent->last_seen_at)->not->toBeNull();
});
