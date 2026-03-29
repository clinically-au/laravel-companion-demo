<?php

test('it returns migration history grouped by batch', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/migrations');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    $response->assertJsonStructure([
        'data' => [
            'batches',
            'total',
        ],
    ]);
});

test('it includes total migration count', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/migrations');

    expect($response->json('data.total'))->toBeGreaterThan(0);
});
