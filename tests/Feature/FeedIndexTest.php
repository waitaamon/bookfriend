<?php

use App\Models\User;
use App\Models\Book;
use \Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('only allow authenticated user to post')
    ->expectGuest()
    ->toBeRedirectedFor('/feed');

it('shows books of friends', function () {
    $user = User::factory()->create();
    $friendOne = User::factory()->create();
    $friendTwo = User::factory()->create();

    $friendOne->books()->attach($bookOne = Book::factory()->create(), [
        'status' => 'READING',
        'updated_at' => now()->addDay()
    ]);

    $friendTwo->books()->attach($bookTwo = Book::factory()->create(), [
        'status' => 'WANT_TO_READ'
    ]);

    $user->addFriend($friendOne);
    $friendOne->acceptFriend($user);

    $friendTwo->addFriend($user);
    $user->acceptFriend($friendTwo);

    actingAs($user)
        ->get('/feed')
        ->assertSeeInOrder([
            $friendOne->name . ' is reading ' . $bookOne->title,
            $friendTwo->name . ' wants to read ' . $bookTwo->title,
        ]);
});
