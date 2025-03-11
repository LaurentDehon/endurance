module.exports = {
    content: [
        // Laravel Blade views
        './resources/**/*.blade.php',
        
        // Livewire views
        './resources/**/*.livewire.php',
        
        // Alpine.js components
        './resources/**/*.js',
        './resources/**/*.vue',

        // WireUI views and components
        './vendor/wireui/wireui/src/*.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/WireUi/**/*.php',
        './vendor/wireui/wireui/src/Components/**/*.php',
    ],
    presets: [
        // Import WireUI's Tailwind configuration
        require("./vendor/wireui/wireui/tailwind.config.js")
    ],
    theme: {
        extend: {
            // Tu peux étendre les couleurs, typographies, espacements, etc.
        },
    },
    plugins: [
        // Ajouter des plugins si nécessaire
        require('@tailwindcss/forms'),  // Exemples de plugins supplémentaires
        require('@tailwindcss/typography'),
    ],
}
