<?php

if (!function_exists('themeClass')) {
    /**
     * Récupère les classes CSS pour un élément spécifique du thème actif
     * 
     * @param string $key La clé de l'élément du thème (ex: 'text-primary', 'background', etc.)
     * @param string $default Classes CSS par défaut si la clé n'existe pas
     * @return string Les classes CSS à appliquer
     */
    function themeClass($key, $default = '') {
        // Récupération du thème préféré de l'utilisateur depuis la session
        $themeName = session('theme_preference', config('themes.default', 'blue'));
        
        // Vérification si le thème existe dans la configuration
        if (!array_key_exists($themeName, config('themes.themes', []))) {
            $themeName = 'blue'; // Thème par défaut si celui demandé n'existe pas
        }
        
        // Récupération des données du thème
        $theme = config('themes.themes.'.$themeName);
        
        return isset($theme['colors'][$key]) ? implode(' ', $theme['colors'][$key]) : $default;
    }
}