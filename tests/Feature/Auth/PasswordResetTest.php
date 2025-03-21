<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Get the password reset page first
    $this->get('/forgot-password');

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
        '_token' => session('_token'),
    ]);

    Notification::assertSentTo(
        $user,
        ResetPassword::class
    );
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Get the password reset page first
    $this->get('/forgot-password');

    $this->post('/forgot-password', [
        'email' => $user->email,
        '_token' => session('_token'),
    ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/' . $notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    // Request password reset
    $this->get('/forgot-password');
    $this->post('/forgot-password', [
        'email' => $user->email,
        '_token' => session('_token'),
    ]);

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
        // Get the reset page to establish new session
        $this->get('/reset-password/' . $notification->token);

        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
            '_token' => session('_token'),
        ]);

        $response->assertSessionHasNoErrors();

        return true;
    });
});
