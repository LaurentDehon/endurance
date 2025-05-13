<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Liste des langues supportées par l'application
     * 
     * @var array
     */
    protected $availableLocales = ['en', 'fr'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Priorité 1: Langue stockée en session
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } 
        // Priorité 2: Langue stockée dans les paramètres utilisateur si connecté
        else if (Auth::check()) {
            $locale = Auth::user()->settings['language'] ?? config('app.locale');
            App::setLocale($locale);
            
            // Stocker en session pour les requêtes suivantes
            Session::put('locale', $locale);
        }
        // Priorité 3: Langue du navigateur pour les utilisateurs non connectés
        else if ($preferredLocale = $this->getPreferredLocaleFromBrowser($request)) {
            App::setLocale($preferredLocale);
            
            // Stocker en session pour les requêtes suivantes
            Session::put('locale', $preferredLocale);
        }
        // Priorité 4: Langue par défaut de l'application
        else {
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }

    /**
     * Détermine la langue préférée du navigateur parmi les langues disponibles
     *
     * @param Request $request
     * @return string|null
     */
    protected function getPreferredLocaleFromBrowser(Request $request)
    {
        if (!$request->header('Accept-Language')) {
            return null;
        }

        // Analyser les langues préférées du navigateur
        $browserLocales = array_reduce(
            explode(',', $request->header('Accept-Language')),
            function ($result, $item) {
                $parts = explode(';q=', $item);
                $locale = trim($parts[0]);
                $quality = isset($parts[1]) ? (float) $parts[1] : 1.0;
                $result[$locale] = $quality;
                return $result;
            },
            []
        );
        
        // Trier par qualité
        arsort($browserLocales);
        
        // Trouver la première correspondance
        foreach ($browserLocales as $browserLocale => $quality) {
            // Extraire le code de langue principal (ex: fr-FR -> fr)
            $mainLocale = substr($browserLocale, 0, 2);
            
            if (in_array($mainLocale, $this->availableLocales)) {
                return $mainLocale;
            }
        }
        
        return null;
    }
}
