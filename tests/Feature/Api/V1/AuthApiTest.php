<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('logs in with email and password and returns a token', function () {
    $user = reportingUser('ApiLogin');

    $response = $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'android-test',
    ]);

    $response->assertSuccessful()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('user.email', $user->email)
        ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'employee']]);

    expect($response->json('token'))->not->toBeEmpty();
});

it('rejects invalid login credentials', function () {
    $user = reportingUser('ApiBadLogin');

    $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertUnprocessable();
});

it('returns the authenticated user from me', function () {
    $user = reportingUser('ApiMe');

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/me')
        ->assertSuccessful()
        ->assertJsonPath('data.email', $user->email);
});

it('logs out and revokes the current token', function () {
    $user = reportingUser('ApiLogout');
    $token = reportingApiToken($user);

    expect($user->tokens()->count())->toBe(1);

    $this->withToken($token)
        ->postJson('/api/v1/logout')
        ->assertSuccessful();

    expect($user->fresh()->tokens()->count())->toBe(0);

    // Sanctum sets the user on the guard during the logout request; clear it
    // before asserting the revoked token is rejected on a fresh request.
    $this->app['auth']->forgetGuards();

    $this->withToken($token)
        ->getJson('/api/v1/me')
        ->assertUnauthorized();
});

it('requires authentication for me', function () {
    $this->getJson('/api/v1/me')->assertUnauthorized();
});
