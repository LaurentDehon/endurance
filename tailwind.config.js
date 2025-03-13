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
        // './vendor/wireui/wireui/src/*.php',
        // './vendor/wireui/wireui/ts/**/*.ts',
        // './vendor/wireui/wireui/src/WireUi/**/*.php',
        // './vendor/wireui/wireui/src/Components/**/*.php',

        // TallstackUI views and components
        './vendor/tallstackui/tallstackui/src/**/*.php',
    ],
    presets: [
        // Import WireUI's Tailwind configuration
        // require("./vendor/wireui/wireui/tailwind.config.js")
        require('./vendor/tallstackui/tallstackui/tailwind.config.js') 
    ],
    theme: {
        extend: {
            // Tu peux étendre les couleurs, typographies, espacements, etc.
        },
    },
    plugins: [
        // Ajouter des plugins si nécessaire
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
    safelist: [
        {
            pattern: /^bg-(slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-(50|100|200|300|400|500|600|700|800|900|950)$/,
            variants: ['hover', 'focus'] // optionnel
        },
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl']
        } 
      ]
}
