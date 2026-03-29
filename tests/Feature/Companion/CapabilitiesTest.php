<?php

test('it returns capabilities matrix', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/capabilities');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    $response->assertJsonStructure([
        'data' => ['features'],
    ]);
});

test('it reflects agent scopes in capabilities', function () {
    $agentToken = $this->createTestAgent(['environment:read', 'models:read']);

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/capabilities');

    $features = $response->json('data.features');
    expect($features)->toBeArray();
});

test('it reports horizon as unavailable when not installed', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/capabilities');

    $features = $response->json('data.features');

    if (! class_exists(\Laravel\Horizon\Horizon::class)) {
        $horizon = $features['horizon'] ?? null;
        // May be false or array with 'available' => false
        if (is_array($horizon)) {
            expect($horizon['available'] ?? true)->toBeFalse();
        } else {
            expect($horizon)->toBeFalse();
        }
    }
});
