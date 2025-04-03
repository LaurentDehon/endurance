@php
// Vérification de sécurité pour s'assurer que la variable $theme est définie
if (!isset($theme) || !is_array($theme)) {
    // Utiliser le thème par défaut si $theme n'est pas défini
    $theme = config('themes.themes.' . config('themes.default', 'blue'));
}

// Récupérer les classes pour les cartes
$cardBgClasses = isset($theme['colors']['card-bg']) ? implode(' ', $theme['colors']['card-bg']) : 'bg-white bg-opacity-10';
$cardBorderClasses = isset($theme['colors']['card-border']) ? implode(' ', $theme['colors']['card-border']) : 'border-white border-opacity-20';
@endphp

<div class="{{ $cardBgClasses }} backdrop-blur-lg rounded-xl p-6 shadow-xl border {{ $cardBorderClasses }} transform hover:scale-105 transition-all duration-300 {{ $attributes->get('class') }}">
    {{ $slot }}
</div>