<?php

test('it lists all artisan commands', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/commands');

    $response->assertOk();
    $this->assertCompanionResponse($response);

    $commands = collect($response->json('data'));
    expect($commands->pluck('name'))->toContain('cache:clear');
});

test('it lists whitelisted commands', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/commands/whitelisted');

    $response->assertOk();

    $commands = collect($response->json('data'));
    expect($commands->pluck('name'))->toContain('cache:clear', 'inspire');
});

test('it executes a whitelisted command', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->postJson('companion/api/commands/cache:clear/run');

    $response->assertOk();
    $response->assertJsonPath('data.exit_code', 0);
});

test('it blocks blacklisted command', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->postJson('companion/api/commands/migrate/run');

    $response->assertStatus(403);
    $this->assertCompanionError($response, 'command_blacklisted', 403);
});

test('it blocks non-whitelisted command', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->postJson('companion/api/commands/route:list/run');

    $response->assertStatus(403);
    $this->assertCompanionError($response, 'command_not_whitelisted', 403);
});

test('it denies execution without execute scope', function () {
    $agentToken = $this->createTestAgent(['commands:list']);

    $this->withCompanionAgent($agentToken)
        ->postJson('companion/api/commands/cache:clear/run')
        ->assertStatus(403);
});
