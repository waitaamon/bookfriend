<?php

use App\Models\User;
use App\Models\Book;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('it can have pending friends', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $user->addFriend($friend);

    expect($user->pendingFriendsOfMine)->toHaveCount(1);
});

it('it can have friends request', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $friend->addFriend($user);

    expect($user->pendingFriendsOf)->toHaveCount(1);
});

it('does not create duplicate friend request', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $user->addFriend($friend);
    $user->addFriend($friend);

    expect($user->pendingFriendsOfMine)->not()->toHaveCount(2);
});

it('can accept friends', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $user->addFriend($friend);
    $friend->acceptFriend($user);

    expect($user->acceptedFriendsOfMine)
        ->toHaveCount(1)
        ->pluck('id')->toContain($friend->id);
});

it('can get all friends', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();
    $anotherFriend = User::factory()->create();
    $yetAnotherFriend = User::factory()->create();

    $user->addFriend($friend);
    $user->addFriend($yetAnotherFriend);
    $user->addFriend($anotherFriend); //not accepted

    $friend->acceptFriend($user);
    $yetAnotherFriend->acceptFriend($user);

    expect($user->friends)->toHaveCount(2)
        ->and($friend->friends)->toHaveCount(1)
        ->and($yetAnotherFriend->friends)->toHaveCount(1)
        ->and($anotherFriend->friends)->toHaveCount(0);
});

it('can remove a friend', function () {
    $user = User::factory()->create();
    $friend = User::factory()->create();

    $user->addFriend($friend);
    $friend->acceptFriend($user);
    $user->removeFriend($friend);

    expect($user->friends)->toHaveCount(0)
        ->and($friend->friends)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(0);
});

it('can get books of friends', function () {
    $user = User::factory()->create();
    $friendOne = User::factory()->create();
    $friendTwo = User::factory()->create();
    $friendThree = User::factory()->create();

    $friendOne->books()->attach($bookOne = Book::factory()->create(), [
        'status' => 'WANT_TO_READ',
        'updated_at' => now()
    ]);

    $friendTwo->books()->attach($bookTwo = Book::factory()->create(), [
        'status' => 'WANT_TO_READ',
        'updated_at' => now()->subDay()
    ]);

    $friendThree->books()->attach($bookThree = Book::factory()->create(), [
        'status' => 'WANT_TO_READ'
    ]);

    $user->addFriend($friendOne);
    $friendOne->acceptFriend($user);

    $friendTwo->addFriend($user);
    $user->acceptFriend($friendTwo);

    $user->addFriend($friendThree);

    expect($user->booksOfFriends)
        ->count()->toBe(2)
        ->first()->title->toBe($bookOne->title);

});
