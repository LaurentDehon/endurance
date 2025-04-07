module.exports = {
    content: [
        // Laravel Blade views
        './resources/**/*.blade.php',
        
        // Livewire views
        './resources/**/*.livewire.php',
        
        // Alpine.js components
        './resources/**/*.js',
        './resources/**/*.vue',

        // TallstackUI views and components
        './vendor/tallstackui/tallstackui/src/**/*.php',
    ],
    presets: [
        // Import TallstackUI's Tailwind configuration
        require('./vendor/tallstackui/tallstackui/tailwind.config.js') 
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Roboto', 'sans-serif'],
              },
        },
    },
    plugins: [
        // Ajouter des plugins si nécessaire
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
    safelist: [
        {
            pattern: /^(bg|from|via|to)-(slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-(50|100|200|300|400|500|600|700|800|900|950)$/,
            variants: ['hover', 'focus']
        },
        {
            pattern: /bg-gradient-to-(tr|br|tl|bl)/
        },
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl']
        },
        {
            pattern: /text-(slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose)-(50|100|200|300|400|500|600|700|800|900|950)$/,
        }
    ]
}
