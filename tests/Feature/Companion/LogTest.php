<?php

use Illuminate\Support\Facades\Log;

test('it lists log files', function () {
    Log::info('Test log entry for listing');

    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/logs');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    expect($response->json('data'))->toBeArray();
});

test('it returns parsed log entries', function () {
    Log::info('Companion test log entry');

    $agentToken = $this->createTestAgent();

    $logsResponse = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/logs');

    $files = $logsResponse->json('data');
    if (empty($files)) {
        $this->markTestSkipped('No log files available');
    }

    $fileName = $files[0]['name'];

    $response = $this->withCompanionAgent($agentToken)
        ->getJson("companion/api/logs/{$fileName}");

    $response->assertOk();
});

test('it returns 404 for missing log file', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/logs/nonexistent.log');

    $response->assertStatus(404);
});
