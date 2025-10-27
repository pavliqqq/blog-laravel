<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('successfully logs out and revokes the token', function () {
    User::factory()->create([
        'name' => 'userName',
        'email' => 'user@gmail.com',
        'password' => Hash::make('12345678'),
    ]);

    $response = $this->postJson("/api/login", [
        'email' => 'user@gmail.com',
        'password' => '12345678',
    ]);

    $this->assertDatabaseCount('personal_access_tokens', 1);

    $token = $response->json('token');

    $this->withToken($token)
        ->postJson("/api/logout")
        ->assertOk()
        ->assertJsonStructure(['message']);

    $this->assertDatabaseCount('personal_access_tokens', 0);
});

it('returns unauthorized when user tries to logout without authentication', function () {
    $this->postJson("/api/logout")
        ->assertUnauthorized();
});
