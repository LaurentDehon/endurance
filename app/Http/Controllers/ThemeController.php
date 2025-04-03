<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ThemeController extends Controller
{
    /**
     * Changer le thème de l'application.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchTheme(Request $request)
    {
        $themeName = $request->theme;
        
        // Vérifier si le thème existe dans la configuration
        if (array_key_exists($themeName, config('themes.themes'))) {
            // Stocker dans la session
            Session::put('theme_preference', $themeName);
            
            // Si l'utilisateur est connecté, stocker dans la base de données
            if (Auth::check()) {
                $user = Auth::user();
                $user->theme_preference = $themeName;
                $user->save();
            }
        }
        
        // Rediriger vers la page précédente
        return back();
    }
}