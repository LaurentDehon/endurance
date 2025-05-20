<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(): RedirectResponse
    {
        // Vérification des IP bannies
        $ipAddress = request()->ip();
        if (\App\Models\BannedIp::isIpBanned($ipAddress)) {
            return redirect()->route('login')
                ->with('error', 'Votre adresse IP a été bannie. Veuillez contacter un administrateur.');
        }

        // Validation du formulaire
        request()->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Vérification du rate limiting
        $this->ensureIsNotRateLimited();

        // Tentative d'authentification
        if (!Auth::attempt(request()->only('email', 'password'), request()->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Succès d'authentification, reset le rate limiting
        RateLimiter::clear($this->throttleKey());

        // Régénération de session
        request()->session()->regenerate();

        return redirect()->intended(route('home', absolute: false));
    }

    public function destroy(): RedirectResponse
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower(request()->input('email')).'|'.request()->ip());
    }
}
