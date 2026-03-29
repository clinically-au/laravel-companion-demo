<?php

test('it allows access with correct scope', function () {
    $agentToken = $this->createTestAgent(['environment:read']);

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/environment')
        ->assertOk();
});

test('it denies access with wrong scope', function () {
    $agentToken = $this->createTestAgent(['models:read']);

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/environment')
        ->assertStatus(403);
});

test('it allows wildcard scope', function () {
    $agentToken = $this->createTestAgent(['*']);

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/environment')
        ->assertOk();
});

test('it allows partial wildcard *:read', function () {
    $agentToken = $this->createTestAgent(['*:read']);

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/environment')
        ->assertOk();
});

test('it denies partial wildcard *:read for write scope', function () {
    $agentToken = $this->createTestAgent(['*:read']);

    $this->withCompanionAgent($agentToken)
        ->postJson('companion/api/commands/cache:clear/run')
        ->assertStatus(403);
});
