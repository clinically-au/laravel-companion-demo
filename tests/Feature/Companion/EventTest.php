<?php

test('it returns event listener mapping', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/events');

    $response->assertOk();
    $this->assertCompanionResponse($response);
    expect($response->json('data'))->toBeArray();
});

test('it includes PostPublished event', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/events');

    $events = collect($response->json('data'));
    $postPublished = $events->firstWhere('event', 'App\\Events\\PostPublished');

    expect($postPublished)->not->toBeNull();
    expect($postPublished['listeners'])->not->toBeEmpty();
});

test('it lists listeners for PostPublished', function () {
    $agentToken = $this->createTestAgent();

    $response = $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/events');

    $events = collect($response->json('data'));
    $postPublished = $events->firstWhere('event', 'App\\Events\\PostPublished');

    $listeners = collect($postPublished['listeners']);
    expect($listeners)->toHaveCount(2);

    $listenerClasses = $listeners->pluck('class')->toArray();
    expect($listenerClasses)->toContain('App\\Listeners\\SendPostPublishedNotification@handle');
    expect($listenerClasses)->toContain('App\\Listeners\\UpdateSearchIndex@handle');
});
