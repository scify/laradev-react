<?php

use App\Models\User;

test('profile page is displayed', function () {
    $user = User::factory()->create();
    $url = 'settings/profile';

    $response = $this
        ->actingAs($user)
        ->get($url);

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();
    $url = 'settings/profile';

    $this->actingAs($user)->get($url);

    $response = $this
        ->actingAs($user)
        ->patch($url, [
            'name' => 'Test User',
            'email' => 'test@example.com',
            '_token' => session('_token'),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($url);

    $user->refresh();

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();
    $url = 'settings/profile';

    $this->actingAs($user)->get($url);

    $response = $this
        ->actingAs($user)
        ->patch($url, [
            'name' => 'Test User',
            'email' => $user->email,
            '_token' => session('_token'),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect($url);

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();
    $url = 'settings/profile';

    $this->actingAs($user)->get($url);

    $response = $this
        ->actingAs($user)
        ->delete($url, [
            'password' => 'password',
            '_token' => session('_token'),
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    expect($user->fresh())->toBeNull();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $url = 'settings/profile';

    $this->actingAs($user)->get($url);

    $response = $this
        ->actingAs($user)
        ->from($url)
        ->delete($url, [
            'password' => 'wrong-password',
            '_token' => session('_token'),
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect($url);

    expect($user->fresh())->not->toBeNull();
});
