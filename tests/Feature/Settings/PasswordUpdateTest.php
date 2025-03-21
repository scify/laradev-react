<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

test('password can be updated', function () {
    $user = User::factory()->create();

    $url = 'settings/password';
    // Get the password update page first to establish session
    $this->actingAs($user)->get($url);

    $response = $this->actingAs($user)
        ->from($url)
        ->put($url, [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            '_token' => session('_token'),
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect($url);

    expect(Hash::check('new-password', $user->refresh()->password))->toBeTrue();
});

test('correct password must be provided to update password', function () {
    $user = User::factory()->create();

    $url = 'settings/password';
    $this->actingAs($user)->get($url);

    $response = $this
        ->actingAs($user)
        ->from($url)
        ->put($url, [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            '_token' => session('_token'),
        ]);

    $response
        ->assertSessionHasErrors('current_password')
        ->assertRedirect($url);
});
