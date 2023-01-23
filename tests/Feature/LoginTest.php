<?php

use App\Models\User;
use \Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


it('show login page')->get('/auth/login')->assertStatus(200);

it('redirect authenticated user', function () {
    expect(User::factory()->create())->toBeRedirectedFor('/auth/login');
});

it('shows an error if details are not provided')
    ->post('/login')
    ->assertSessionHasErrors(['email', 'password']);

it('logs the user in', function () {
    $user = User::factory()->create([
        'password' => bcrypt('pass1234')
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'pass1234'
    ])
        ->assertRedirect('/');

    $this->assertAuthenticated();
});
