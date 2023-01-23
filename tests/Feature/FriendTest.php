<?php

use App\Models\User;
use \Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirect unauthenticated users')
    ->expectGuest()
    ->toBeRedirectedFor('/friends');

it('shows a list of users showing friend request', function () {
    $user = User::factory()->create();
    $friends = User::factory()->times(2)->create();

    $friends->each(fn($friend) => $user->addFriend($friend));

    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(['Pending friend request', ...$friends->pluck('name')->toArray()]);
});

it('shows a list of users friend requests', function () {
    $user = User::factory()->create();
    $friends = User::factory()->times(2)->create();

    $friends->each(fn($friend) => $friend->addFriend($user));

    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(['Friend requests', ...$friends->pluck('name')->toArray()]);
});

it('shows a list of users of accepted friends', function () {
    $user = User::factory()->create();
    $friends = User::factory()->times(2)->create();

    $friends->each(function($friend) use ($user) {
        $user->addFriend($friend);
        $friend->acceptFriend($user);
    });

    actingAs($user)
        ->get('/friends')
        ->assertOk()
        ->assertSeeTextInOrder(['Friends', ...$friends->pluck('name')->toArray()]);
});
