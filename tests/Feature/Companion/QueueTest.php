<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

test('it returns queue connection info', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/queues');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    $response->assertJsonStructure([
        'data' => ['connection', 'driver'],
    ]);
});

test('it lists failed jobs', function () {
    DB::table('failed_jobs')->insert([
        'uuid' => Str::uuid()->toString(),
        'connection' => 'database',
        'queue' => 'default',
        'payload' => json_encode(['job' => 'test']),
        'exception' => 'RuntimeException: Test failure',
        'failed_at' => now(),
    ]);

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/queues/failed');

    $response->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

test('it shows failed job detail', function () {
    $uuid = Str::uuid()->toString();
    DB::table('failed_jobs')->insert([
        'uuid' => $uuid,
        'connection' => 'database',
        'queue' => 'default',
        'payload' => json_encode(['job' => 'test']),
        'exception' => 'RuntimeException: Test failure with full stack trace',
        'failed_at' => now(),
    ]);

    $id = DB::table('failed_jobs')->first()->id;
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson("companion/api/queues/failed/{$id}");

    $response->assertOk();
    $this->assertCompanionResponse($response);
});

test('it deletes a failed job', function () {
    DB::table('failed_jobs')->insert([
        'uuid' => Str::uuid()->toString(),
        'connection' => 'database',
        'queue' => 'default',
        'payload' => json_encode(['job' => 'test']),
        'exception' => 'RuntimeException: Test failure',
        'failed_at' => now(),
    ]);

    $id = DB::table('failed_jobs')->first()->id;
    $agentToken = $this->createTestAgent();

    $this->withCompanionAgent($agentToken)
        ->deleteJson("companion/api/queues/failed/{$id}")
        ->assertOk();

    expect(DB::table('failed_jobs')->count())->toBe(0);
});

test('it requires confirmation for flush', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->deleteJson('companion/api/queues/failed');

    $response->assertStatus(422);
    $this->assertCompanionError($response, 'confirmation_required', 422);
});

test('it flushes failed jobs with confirmation', function () {
    DB::table('failed_jobs')->insert([
        'uuid' => Str::uuid()->toString(),
        'connection' => 'database',
        'queue' => 'default',
        'payload' => json_encode(['job' => 'test']),
        'exception' => 'Test',
        'failed_at' => now(),
    ]);

    $agentToken = $this->createTestAgent();

    $this->withCompanionAgent($agentToken)
        ->deleteJson('companion/api/queues/failed', ['confirm' => true])
        ->assertOk();

    expect(DB::table('failed_jobs')->count())->toBe(0);
});
