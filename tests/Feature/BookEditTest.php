<?php

use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirect unauthenticated users')
    ->expectGuest()
    ->toBeRedirectedFor('/books/1/edit');

it('shows the book details in the form', function () {
    $user = User::factory()->create();

    $user->books()->attach($book = Book::factory()->create(), [
        'status' => 'READING'
    ]);

    actingAs($user)
        ->get('/books/' . $book->id . '/edit')
        ->assertOk()
        ->assertSee([$book->title, $book->author])
        ->assertSee('<option value="READING" selected>reading</option>', false);
});

it('fails if the user does not own the book', function () {
    $user = User::factory()->create();

    $anotherUser = User::factory()->create();

    $anotherUser->books()->attach($book = Book::factory()->create(), [
        'status' => 'READING'
    ]);

    actingAs($user)
        ->get('/books/' . $book->id . '/edit')
        ->assertStatus(403);
});
