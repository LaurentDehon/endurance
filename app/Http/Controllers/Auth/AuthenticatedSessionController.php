<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Vérifier si l'adresse IP est bannie avant l'authentification
        $ipAddress = $request->ip();
        if (\App\Models\BannedIp::isIpBanned($ipAddress)) {
            return redirect()->route('login')
                ->with('error', 'Votre adresse IP a été bannie. Veuillez contacter un administrateur.');
        }
        
        $request->authenticate();

        $request->session()->regenerate();
        
        // Mettre à jour la dernière adresse IP utilisée par l'utilisateur
        $user = Auth::user();
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'last_ip_address' => $ipAddress,
                'last_login_at' => now()
            ]);

        return redirect()->intended(route('home', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
