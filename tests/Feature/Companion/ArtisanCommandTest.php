<?php

use Clinically\Companion\Models\CompanionAgent;
use Clinically\Companion\Models\CompanionAuditLog;
use Illuminate\Support\Facades\Artisan;

test('companion commands are registered', function () {
    $commands = array_keys(Artisan::all());

    expect($commands)->toContain(
        'companion:status',
        'companion:agents',
        'companion:prune-audit',
    );
});

test('it runs companion:status', function () {
    $this->artisan('companion:status')
        ->assertSuccessful();
});

test('it runs companion:agents with no agents', function () {
    $this->artisan('companion:agents')
        ->assertSuccessful();
});

test('it runs companion:agents with agents', function () {
    $this->createTestAgent(name: 'Listed Agent');

    $this->artisan('companion:agents')
        ->assertSuccessful();
});

test('it runs companion:prune-audit', function () {
    CompanionAuditLog::create([
        'agent_id' => $this->createTestAgent()->agent->id,
        'action' => 'test',
        'method' => 'GET',
        'path' => '/test',
        'response_code' => 200,
        'ip' => '127.0.0.1',
        'user_agent' => 'Test',
        'duration_ms' => 10,
        'created_at' => now()->subDays(100),
    ]);

    $this->artisan('companion:prune-audit')
        ->assertSuccessful();
});
