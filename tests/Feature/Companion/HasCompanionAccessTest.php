<?php

use App\Models\User;
use Clinically\Companion\Models\CompanionAgent;

test('it creates a companion agent from user', function () {
    $user = User::factory()->create();

    $agentToken = $user->createCompanionAgent('Test Agent', ['*']);

    expect($agentToken->agent)->toBeInstanceOf(CompanionAgent::class);
    expect($agentToken->agent->name)->toBe('Test Agent');
    expect($agentToken->agent->created_by)->toBe($user->id);
    expect($agentToken->plainToken)->not->toBeEmpty();
});

test('it lists companion agents for user', function () {
    $user = User::factory()->create();
    $user->createCompanionAgent('Agent 1', ['*']);
    $user->createCompanionAgent('Agent 2', ['models:read']);

    expect($user->companionAgents)->toHaveCount(2);
});

test('it revokes companion agent from user', function () {
    $user = User::factory()->create();
    $agentToken = $user->createCompanionAgent('To Revoke', ['*']);

    $user->revokeCompanionAgent($agentToken->agent);

    $agentToken->agent->refresh();
    expect($agentToken->agent->isRevoked())->toBeTrue();
});

test('it checks canAccessCompanion', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    expect($user->canAccessCompanion())->toBeTrue();
});

test('it returns audit log for user agents', function () {
    $user = User::factory()->create();
    $agentToken = $user->createCompanionAgent('Audit Test', ['*']);

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertOk();

    $auditLogs = $user->companionAuditLog()->get();
    expect($auditLogs)->not->toBeEmpty();
});
