@php
// Vérification de sécurité pour s'assurer que la variable $theme est définie
if (!isset($theme) || !is_array($theme)) {
    // Utiliser le thème par défaut si $theme n'est pas défini
    $theme = config('themes.themes.' . config('themes.default', 'blue'));
}

// Récupération du thème actuel depuis la session
$themeName = session('theme_preference', config('themes.default', 'blue'));

// Helper function pour obtenir les classes de thème en toute sécurité
function getSelectorThemeClass($key, $default = '') {
    global $theme;
    return isset($theme['colors'][$key]) ? implode(' ', $theme['colors'][$key]) : $default;
}

// Récupération des thèmes disponibles de façon sécurisée
$availableThemes = config('themes.themes');
if (!is_array($availableThemes)) {
    // Définir des thèmes par défaut si la configuration n'est pas disponible
    $availableThemes = [
        'blue' => [
            'name' => 'Blue Performance',
            'colors' => [
                'text-primary' => ['text-white'],
                'button-bg' => ['bg-blue-500'],
            ]
        ],
        'nature' => [
            'name' => 'Nature & Endurance',
            'colors' => [
                'text-primary' => ['text-[#FAF3E0]'],
                'button-bg' => ['bg-[#E76F51]'],
            ]
        ]
    ];
}
@endphp

<div class="theme-selector" x-data="{ themeMenuOpen: false }">
    <div class="px-4 py-2">
        <div class="flex items-center justify-between">
            <span class="text-gray-700 font-medium">Thème</span>
            <button @click="themeMenuOpen = !themeMenuOpen" type="button" 
                class="flex items-center space-x-2 text-gray-600 focus:outline-none">
                <span>{{ $theme['name'] }}</span>
                <i class="fas fa-chevron-down text-xs" :class="{ 'transform rotate-180': themeMenuOpen }"></i>
            </button>
        </div>
        
        <div x-show="themeMenuOpen" x-cloak class="mt-2 border rounded-lg bg-white overflow-hidden">
            <form method="POST" action="{{ route('theme.switch') }}" id="mobilethemeForm">
                @csrf
                <div class="divide-y divide-gray-100">
                    @foreach($availableThemes as $key => $themeOption)
                        <button type="submit" name="theme" value="{{ $key }}" 
                            class="w-full text-left flex items-center px-4 py-2 text-sm {{ $themeName === $key ? 'bg-gray-50 text-gray-900 font-medium' : 'text-gray-700 hover:bg-gray-50' }}">
                            <span class="w-3 h-3 mr-3 rounded-full" style="background: linear-gradient(to bottom right, {{ str_replace(['from-', 'via-', 'to-'], '', implode(', ', $themeOption['colors']['background'])) }});"></span>
                            {{ $themeOption['name'] }}
                        </button>
                    @endforeach
                </div>
            </form>
        </div>
    </div>
</div>