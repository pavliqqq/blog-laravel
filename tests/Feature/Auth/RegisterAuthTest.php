<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('registers with correct data', function () {
    $this->assertDatabaseCount('personal_access_tokens', 0);

    $response = $this->postJson("/api/register", [
        'name' => 'userName',
        'email' => 'user@gmail.com',
        'password' => '12345678',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token']);

    $this->assertDatabaseCount('personal_access_tokens', 1);
});

it('returns unprocessable when user trying to register with email that already exists', function () {
    User::factory()->create([
        'email' => 'email@gmail.com',
    ]);

    $response = $this->postJson("/api/register", [
        'name' => 'simpleName',
        'email' => 'email@gmail.com',
        'password' => '12345678',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

it('returns unprocessable when tries to register with empty email', function () {
    $response = $this->postJson("/api/register", [
        'email' => '',
        'password' => '12345678',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

it('returns unprocessable when tries to register with empty password', function () {
    $response = $this->postJson("/api/register", [
        'email' => 'user@gmail.com',
        'password' => '',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('password');
});
