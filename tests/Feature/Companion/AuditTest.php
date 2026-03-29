<?php

use Clinically\Companion\Models\CompanionAuditLog;

test('it logs API requests to audit table', function () {
    $agentToken = $this->createTestAgent();

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertOk();

    expect(CompanionAuditLog::count())->toBeGreaterThanOrEqual(1);

    $log = CompanionAuditLog::latest('created_at')->first();
    expect($log->agent_id)->toBe($agentToken->agent->id);
    expect($log->method)->toBe('GET');
    expect($log->path)->toContain('ping');
});

test('it records write operations', function () {
    $agentToken = $this->createTestAgent();

    $this->withCompanionAgent($agentToken)
        ->postJson('companion/api/commands/cache:clear/run')
        ->assertOk();

    $log = CompanionAuditLog::where('method', 'POST')->latest('created_at')->first();
    expect($log)->not->toBeNull();
    expect($log->method)->toBe('POST');
});

test('it records response code', function () {
    $agentToken = $this->createTestAgent();

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertOk();

    $log = CompanionAuditLog::latest('created_at')->first();
    expect($log->response_code)->toBe(200);
});
