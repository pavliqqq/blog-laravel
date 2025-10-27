<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    User::factory()->create([
        'name' => 'userName',
        'email' => 'user@gmail.com',
        'password' => Hash::make('12345678'),
    ]);
});

it('logins with correct data', function () {
    $this->assertDatabaseCount('personal_access_tokens', 0);

    $response = $this->postJson("/api/login", [
        'email' => 'user@gmail.com',
        'password' => '12345678',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['token']);

    $this->assertDatabaseCount('personal_access_tokens', 1);
});

it('returns unauthorized when tries to login with wrong password', function () {
    $response = $this->postJson("/api/login", [
        'email' => 'user@gmail.com',
        'password' => '123456789',
    ]);

    $response->assertUnauthorized()
        ->assertJsonStructure(['error'])
        ->assertJsonMissing(['token']);
});

it('returns unprocessable when tries to login with empty email', function () {
    $response = $this->postJson("/api/login", [
        'email' => '',
        'password' => '12345678',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

it('returns unprocessable when tries to login with empty password', function () {
    $response = $this->postJson("/api/login", [
        'email' => 'user@gmail.com',
        'password' => '',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrorFor('password');
});
