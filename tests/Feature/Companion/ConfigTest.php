<?php

test('it returns config index with redaction', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/config');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    expect($response->json('data'))->toBeArray()->not->toBeEmpty();
});

test('it returns specific config key', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/config/app.name');

    $response->assertOk();
    $response->assertJsonPath('data.key', 'app.name');
    $response->assertJsonPath('data.value', config('app.name'));
});

test('it redacts app.key', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/config/app.key');

    $response->assertOk();
    $response->assertJsonPath('data.value', '********');
});

test('it does not redact app.name', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/config/app.name');

    $response->assertOk();
    expect($response->json('data.value'))->not->toBe('[REDACTED]');
});
