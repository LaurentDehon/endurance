@php
// Vérification de sécurité pour s'assurer que la variable $theme est définie
if (!isset($theme) || !is_array($theme)) {
    // Utiliser le thème par défaut si $theme n'est pas défini
    $theme = config('themes.themes.' . config('themes.default', 'blue'));
}

// Récupérer les classes pour le texte secondaire
$textClasses = isset($theme['colors']['text-secondary']) ? implode(' ', $theme['colors']['text-secondary']) : 'text-blue-200';
@endphp

<span class="{{ $textClasses }} {{ $attributes->get('class') }}">
    {{ $slot }}
</span>