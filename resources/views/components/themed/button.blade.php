@php
// Vérification de sécurité pour s'assurer que la variable $theme est définie
if (!isset($theme) || !is_array($theme)) {
    // Utiliser le thème par défaut si $theme n'est pas défini
    $theme = config('themes.themes.' . config('themes.default', 'blue'));
}

// Récupérer les classes pour le bouton
$buttonBgClasses = isset($theme['colors']['button-bg']) ? implode(' ', $theme['colors']['button-bg']) : 'bg-blue-500 hover:bg-blue-600';
$buttonTextClasses = isset($theme['colors']['button-text']) ? implode(' ', $theme['colors']['button-text']) : 'text-white';
@endphp

<button {{ $attributes->merge(['class' => $buttonBgClasses . ' ' . $buttonTextClasses . ' p-3 px-6 rounded-full transition-colors shadow-lg']) }}>
    {{ $slot }}
</button>