<?php

return [
    'default' => 'blue',
    
    'themes' => [
        'blue' => [
            'name' => 'Blue Performance',
            'colors' => [
                'background' => ['from-indigo-900', 'via-blue-800', 'to-blue-600'],
                'table'=> ['bg-white','bg-opacity-5'],
                'table-border' => ['border-white', 'border-opacity-20'],
                'text-1' => ['text-white'],
                'text-2' => ['text-blue-200'],
                'text-link' => ['text-blue-500'],
                'nav'=> ['text-white', 'border-white'],
                'nav-active'=> ['border-white', 'text-white'],
                'button' => ['bg-blue-500', 'hover:bg-blue-700','text-white'],
                'button-accent' => ['bg-teal-500', 'hover:bg-teal-400', 'text-white'],
                'button-danger' => ['bg-red-500', 'hover:bg-red-700','text-white'],
                'input' => ['bg-white', 'bg-opacity-10', 'text-white', 'focus:border-teal-400'],
                'mobile-bg'=> ['from-indigo-900', 'via-blue-900', 'to-blue-800'],
                'mobile-nav'=> ['text-white', 'hover:bg-blue-300', 'hover:text-gray-800', 'hover:bg-opacity-50'],
                'mobile-nav-active'=> ['border-l-4', 'border-blue-400', '!border-opacity-100', 'bg-blue-500', 'bg-opacity-40', 'text-white', 'font-medium'],
                'footer-bg' => ['bg-slate-200', 'bg-opacity-95'],
            ],
        ],
        'dark' => [
            'name' => 'Dark Purple',
            'colors' => [
                'background' => ['from-gray-900', 'via-gray-800', 'to-gray-700'],
                'card-bg' => ['bg-gray-800', 'bg-opacity-80'],
                'card-border' => ['border-gray-600', 'border-opacity-30'],
                'text-primary' => ['text-gray-100'],
                'text-2' => ['text-purple-200'],
                'accent-1' => ['bg-purple-500', 'text-white'],
                'link'=> ['text-purple-400', 'hover:text-purple-300'],
                'button-bg' => ['bg-purple-600', 'hover:bg-purple-700'],
                'button-text' => ['text-white'],
                'modal-bg' => ['bg-gradient-to-br', 'from-gray-800', 'to-gray-900'],
                'modal-text' => ['text-gray-200'],
                'modal-text-secondary' => ['text-gray-400'],
                'mobile-bg'=> ['bg-gray-900', 'bg-opacity-95'],
                'mobile-nav'=> ['text-gray-200', 'hover:bg-gray-800', 'hover:bg-opacity-50'],
                'mobile-nav-active'=> ['border-purple-400', 'bg-gray-800', 'bg-opacity-50'],
                'nav'=> ['text-gray-200', 'border-gray-200'],
                'nav-active'=> ['border-purple-400', 'text-white'],
                'text-1' => ['text-white'],
                'footer-bg' => ['bg-gray-900', 'bg-opacity-95'],
                'footer-border' => ['border-gray-700'],
                'footer-text-muted' => ['text-gray-500'],
                'footer-text-brand' => ['text-purple-400'],
                'footer-text-link' => ['text-purple-400', 'hover:text-purple-300'],
            ],
        ]
    ]
];