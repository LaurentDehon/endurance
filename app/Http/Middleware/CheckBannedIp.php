<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\BannedIp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBannedIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Récupérer l'adresse IP de l'utilisateur
        $ipAddress = $request->ip();
        
        // Vérifier si l'adresse IP est bannie
        if (BannedIp::isIpBanned($ipAddress)) {
            // Si l'utilisateur est déjà connecté, on le déconnecte
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            
            // Rediriger vers la page de connexion avec un message d'erreur
            return redirect()->route('login')
                ->with('error', 'Votre adresse IP a été bannie. Veuillez contacter un administrateur.');
        }
        
        return $next($request);
    }
}
