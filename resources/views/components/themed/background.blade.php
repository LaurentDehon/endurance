@php
// Vérification de sécurité pour s'assurer que la variable $theme est définie
if (!isset($theme) || !is_array($theme)) {
    // Utiliser le thème par défaut si $theme n'est pas défini
    $theme = config('themes.themes.' . config('themes.default', 'blue'));
}

// Récupérer les classes de fond d'écran
$backgroundClasses = isset($theme['colors']['background']) ? implode(' ', $theme['colors']['background']) : 'from-indigo-900 via-blue-800 to-blue-600';
@endphp

<div class="bg-gradient-to-br {{ $backgroundClasses }} {{ $attributes->get('class') }}">
    {{ $slot }}
</div>