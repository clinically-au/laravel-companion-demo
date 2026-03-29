<?php

test('it lists all registered routes', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/routes');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    expect($response->json('data'))->toBeArray()->not->toBeEmpty();
});

test('it filters routes by method', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/routes?method=GET');

    $response->assertOk();

    $routes = collect($response->json('data'));
    $routes->each(function ($route) {
        expect($route['methods'])->toContain('GET');
    });
});

test('it filters routes by name prefix', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/routes?name=companion.api');

    $response->assertOk();

    $routes = collect($response->json('data'));
    expect($routes)->not->toBeEmpty();
    $routes->each(function ($route) {
        expect($route['name'])->toStartWith('companion.api');
    });
});

test('it includes companion API routes', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/routes');

    $uris = collect($response->json('data'))->pluck('uri')->toArray();
    expect($uris)->toContain('companion/api/ping');
});

test('it includes demo API routes', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/routes');

    $uris = collect($response->json('data'))->pluck('uri')->toArray();
    expect($uris)->toContain('api/v1/posts');
});
