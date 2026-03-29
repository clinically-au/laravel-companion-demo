<?php

test('it rate limits after exceeding threshold', function () {
    config(['companion.rate_limit.api' => 3]);

    $agentToken = $this->createTestAgent();

    for ($i = 0; $i < 3; $i++) {
        $this->withCompanionAgent($agentToken)
            ->getJson('companion/api/ping')
            ->assertOk();
    }

    $this->withCompanionAgent($agentToken)
        ->getJson('companion/api/ping')
        ->assertStatus(429);
});
