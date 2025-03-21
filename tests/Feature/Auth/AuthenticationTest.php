<?php

use App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    // First get the login page to obtain CSRF token
    $response = $this->get('/login');

    // Then attempt login with the token
    $response = $this->withSession(['_token' => csrf_token()])
        ->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            '_token' => csrf_token(),
        ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
        '_token' => csrf_token(),
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    // First get a page to establish session and CSRF token
    $this->actingAs($user)->get('/dashboard');

    $response = $this->followingRedirects()
        ->post('/logout', [
            '_token' => session('_token'),
        ]);

    $response->assertStatus(200);
    $this->assertGuest();
});
