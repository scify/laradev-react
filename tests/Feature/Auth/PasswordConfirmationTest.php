<?php

use App\Models\User;

test('confirm password screen can be rendered', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/confirm-password');

    $response->assertStatus(200);
});

test('password can be confirmed', function () {
    $user = User::factory()->create();

    // First get the confirmation page to establish session
    $this->actingAs($user)->get('/confirm-password');

    $response = $this->post('/confirm-password', [
        'password' => 'password',
        '_token' => session('_token'),
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});

test('password is not confirmed with invalid password', function () {
    $user = User::factory()->create();

    // First get the confirmation page to establish session
    $this->actingAs($user)->get('/confirm-password');

    $response = $this->post('/confirm-password', [
        'password' => 'wrong-password',
        '_token' => session('_token'),
    ]);

    $response->assertSessionHasErrors();
});
