<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('theme', function ($app) {
            // Récupération du thème préféré de l'utilisateur (depuis session ou BDD)
            $themeName = Session::get('theme_preference');
            
            // Si l'utilisateur est connecté et n'a pas de thème en session, on regarde en BDD
            if (!$themeName && Auth::check()) {
                $themeName = Auth::user()->theme_preference;
                // Sauvegarde du thème en session
                if ($themeName) {
                    Session::put('theme_preference', $themeName);
                }
            }
            
            // Si aucun thème n'est trouvé, on utilise le thème par défaut
            if (!$themeName || !array_key_exists($themeName, config('themes.themes'))) {
                $themeName = config('themes.default');
            }
            
            // Retour des données du thème
            return config('themes.themes.' . $themeName);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Partage du thème avec toutes les vues
        View::share('theme', app('theme'));
    }
}