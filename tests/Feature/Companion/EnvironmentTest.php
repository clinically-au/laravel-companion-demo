<?php

test('it returns environment info', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/environment');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    $response->assertJsonStructure([
        'data' => [
            'app_name',
            'environment',
            'debug',
            'url',
            'timezone',
            'locale',
            'php_version',
            'laravel_version',
            'drivers',
        ],
    ]);
});

test('it includes driver information', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/environment');

    $response->assertJsonStructure([
        'data' => [
            'drivers' => [
                'database',
                'cache',
                'queue',
                'mail',
                'session',
            ],
        ],
    ]);
});
