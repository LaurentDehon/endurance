<?php

return [
    'default' => 'blue',
    
    'themes' => [
        'blue' => [
            'name' => 'Ocean Breeze',
            'colors' => [
                'background' => ['from-indigo-900', 'via-blue-800', 'to-blue-700'],
                'table'=> ['bg-white','bg-opacity-5'],
                'table-border' => ['border-white', 'border-opacity-20'],
                'card' => ['bg-white', 'bg-opacity-10', 'border-white', 'border-opacity-20'],
                'text-1' => ['text-white'],
                'text-2' => ['text-blue-200'],
                'text-3' => ['text-blue-700'],
                'text-link' => ['text-blue-500'],
                'text-accent'=> ['text-orange-400'],
                'check-bg'=> ['from-blue-700', 'to-blue-500'],
                'check-ring'=> ['ring-blue-500'],
                'divider'=> ['border-white', 'border-opacity-20'],
                'nav'=> ['text-white', 'border-white'],
                'nav-active'=> ['border-white', 'text-white'],
                'button' => ['bg-blue-500', 'hover:bg-blue-600','text-white'],
                'button-accent' => ['bg-orange-500', 'hover:bg-orange-600', 'text-white'],
                'button-danger' => ['bg-red-500', 'hover:bg-red-700','text-white'],
                'input' => ['bg-white', 'bg-opacity-10', 'text-white'],
                'mobile-bg'=> ['from-indigo-900', 'via-blue-900', 'to-blue-800'],
                'mobile-nav'=> ['text-white', 'hover:bg-blue-300', 'hover:text-gray-800', 'hover:bg-opacity-50'],
                'mobile-nav-active'=> ['border-l-4', 'border-blue-400', '!border-opacity-100', 'bg-blue-500', 'bg-opacity-40', 'text-white', 'font-medium'],
                'footer-bg' => ['bg-slate-200', 'bg-opacity-95'],
            ],
        ]
    ]
];