<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthenticatedSessionController extends Controller {
    /**
     * Show the login page.
     */
    public function create(Request $request): InertiaResponse|RedirectResponse {
        if (auth()->check()) {
            // User is already logged in, redirect to dashboard
            return redirect()->route(route: 'home');
        }

        return Inertia::render('auth/login', [
            'canResetPassword' => false,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     *
     *
     *
     * @param  LoginRequest  $request  The incoming login request containing credentials
     * @return RedirectResponse|HttpResponse|InertiaResponse Returns either:
     *                                                       - RedirectResponse to dashboard for regular users
     *                                                       - Inertia render response to redirect to home route
     */
    public function store(LoginRequest $request): RedirectResponse|HttpResponse|InertiaResponse {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
