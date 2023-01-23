<?php

use App\Models\User;
use \Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirect unauthenticated users')
    ->expectGuest()
    ->toBeRedirectedFor('/friends', 'post');

it('validates email address is required', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->post('friends')
        ->assertSessionHasErrors(['email']);
});

it('validates email address is exists', function () {
    $user = User::factory()->create();
    actingAs($user)
        ->post('friends', [
            'email' => 'waita@email.com'
        ])
        ->assertSessionHasErrors(['email']);
});

it('cant add self as friend', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->post('friends', [
            'email' => $user->email
        ])
        ->assertSessionHasErrors(['email']);
});

it('stores friend request', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    actingAs($user)
        ->post('friends', [
            'email' => $friend->email
        ]);

    $this->assertDatabaseHas('friends', [
        'user_id' => $user->id,
        'friend_id' => $friend->id,
        'accepted' => false
    ]);
});
