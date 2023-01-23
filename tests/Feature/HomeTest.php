<?php

use App\Models\User;
use function Pest\Laravel\get;
use \Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('greets the user if signed out', function () {
    get('/')
        ->assertSee('Bookfriends')
        ->assertSee('Sign in to get started.')
        ->assertDontSeeText(['Feeds']);
});

it('shows authenticated menu if user is logged in', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertSeeText(['Feed', 'My books', 'Add a book', 'Friend', $user->name]);

});

it('shows unauthenticated menu items if user is logged out', function () {

    get('/')
        ->assertSeeText(['Home', 'Login', 'Register']);
});
