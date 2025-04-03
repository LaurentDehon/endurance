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
        $theme = config('themes.themes.'.$themeName, [
            'name' => 'Blue Performance',
            'colors' => [
                'background' => ['from-indigo-900', 'via-blue-800', 'to-blue-600'],
                'text-primary' => ['text-white'],
                'text-secondary' => ['text-blue-200'],
                'button-bg' => ['bg-blue-500', 'hover:bg-blue-600'],
                'button-text' => ['text-white'],
                'card-bg' => ['bg-white', 'bg-opacity-10'],
                'card-border' => ['border-white', 'border-opacity-20'],
                'modal-bg' => ['bg-white'],
                'modal-text' => ['text-gray-800'],
            ],
        ]);
        
        return isset($theme['colors'][$key]) ? implode(' ', $theme['colors'][$key]) : $default;
    }
}