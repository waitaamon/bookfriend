<?php

use App\Models\User;
use App\Models\Pivot\BookUser;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('only allow authenticated user to post')
    ->expectGuest()
    ->toBeRedirectedFor('/books/create');

it('shows the available statuses on the form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('books/create')
        ->assertSeeTextInOrder(BookUser::$statuses);
});
