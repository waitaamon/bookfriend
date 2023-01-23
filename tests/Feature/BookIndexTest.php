<?php

use App\Models\User;
use App\Models\Book;
use \Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('shows books with the correct status', function ($status, $heading) {
    $this->user->books()->attach($book = Book::factory()->create(), [
        'status' => $status
    ]);

    actingAs($this->user)
        ->get('/')
        ->assertSeeText($heading)
        ->assertSeeText($book->title);
})
->with([
    ['status' => 'WANT_TO_READ', 'heading' => 'want to read'],
    ['status' => 'READING', 'heading' => 'reading'],
    ['status' => 'READ', 'heading' => 'read'],
]);
