<?php

use App\Models\User;

test('it redirects guests from dashboard', function () {
    $this->get('companion/dashboard')
        ->assertRedirect();
});

test('it shows dashboard overview for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('companion/dashboard')
        ->assertOk();
});

test('it shows agent create page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('companion/dashboard/agents/create')
        ->assertOk();
});

test('it shows feature status page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('companion/dashboard/features')
        ->assertOk();
});

test('dashboard routes are registered', function () {
    $routes = collect(app('router')->getRoutes()->getRoutes());

    $dashboardRoutes = $routes->filter(
        fn ($route) => str_starts_with($route->uri(), 'companion/dashboard')
    );

    expect($dashboardRoutes)->not->toBeEmpty();
    expect($dashboardRoutes->pluck('uri')->toArray())->toContain(
        'companion/dashboard',
        'companion/dashboard/agents',
        'companion/dashboard/agents/create',
        'companion/dashboard/features',
        'companion/dashboard/audit',
    );
});
