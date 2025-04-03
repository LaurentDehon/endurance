@php
// Vérification de sécurité pour s'assurer que la variable $theme est définie
if (!isset($theme) || !is_array($theme)) {
    // Utiliser le thème par défaut si $theme n'est pas défini
    $theme = config('themes.themes.' . config('themes.default', 'blue'));
}

// Récupérer les classes pour le texte principal
$textClasses = isset($theme['colors']['text-primary']) ? implode(' ', $theme['colors']['text-primary']) : 'text-white';
@endphp

<span class="{{ $textClasses }} {{ $attributes->get('class') }}">
    {{ $slot }}
</span>