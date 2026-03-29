<?php

test('it returns scheduled commands', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/schedule');

    $response->assertOk();
    $this->assertCompanionResponse($response);

    $commands = collect($response->json('data'));
    expect($commands)->not->toBeEmpty();
});

test('it includes next due date info', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/schedule');

    $firstCommand = $response->json('data.0');
    expect($firstCommand)->toHaveKey('expression');
    expect($firstCommand)->toHaveKey('next_due');
});
