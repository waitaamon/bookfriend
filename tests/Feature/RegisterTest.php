<?php

use App\Models\User;
use \Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows register page')->get('/auth/register')->assertStatus(200);

it('redirect authenticated user', function () {
    expect(User::factory()->create())->toBeRedirectedFor('/auth/register');
});

it('has errors if details are not provided')
    ->post('/register')
    ->assertSessionHasErrors(['name', 'email', 'password']);

it('registers the user')
    ->tap(fn() => $this->post('/register', [
        'name' => 'Amon',
        'email' => 'amon@gmail.com',
        'password' => 'abc123456'
    ])
        ->assertRedirect('/'))
    ->assertDatabaseHas('users', [
        'name' => 'Amon',
        'email' => 'amon@gmail.com'
    ])
    ->assertAuthenticated();
