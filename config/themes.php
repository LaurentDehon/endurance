<?php

return [
    'default' => 'blue',
    
    'themes' => [
        'blue' => [
            'name' => 'Ocean Breeze',
            'colors' => [
                'background' => ['from-slate-900', 'via-blue-900', 'to-slate-800'],
                'table'=> ['bg-white', 'bg-opacity-10'],
                'table-border' => ['border-white', 'border-opacity-20'],
                'table-even' => ['bg-white', 'bg-opacity-10'],
                'table-odd' => ['bg-slate-800', 'bg-opacity-30'],
                'card' => ['bg-white', 'bg-opacity-10', 'border-white', 'border-opacity-20'],
                'text-1' => ['text-white'],
                'text-2' => ['text-cyan-200'],
                'text-3' => ['text-slate-300'],
                'text-link' => ['text-cyan-400'],
                'text-accent'=> ['text-amber-300'],
                'border-accent'=> ['border-amber-300'],
                'bg-accent'=> ['bg-amber-200'],
                'check-bg'=> ['from-cyan-700', 'to-blue-500'],
                'check-ring'=> ['ring-cyan-500'],
                'divider'=> ['border-white', 'border-opacity-20'],
                'nav'=> ['text-slate-100', 'border-cyan-500'],
                'nav-hover'=> ['bg-cyan-800', 'bg-opacity-20', 'text-cyan-200'],
                'nav-active'=> ['border-cyan-400', 'text-white'],
                'button' => ['bg-cyan-600', 'hover:bg-cyan-700','text-white'],
                'button-accent' => ['bg-amber-500', 'hover:bg-amber-600', 'text-white'],
                'button-danger' => ['bg-red-500', 'hover:bg-red-700','text-white'],
                'input' => ['bg-slate-800', 'bg-opacity-60', 'text-white', 'placeholder-gray-400', 'border-slate-600', 'border-opacity-50'],
                'mobile-bg'=> ['from-slate-900', 'via-blue-900', 'to-slate-800'],
                'mobile-nav'=> ['text-white', 'hover:bg-cyan-800', 'hover:bg-opacity-50', 'hover:text-white'],
                'mobile-nav-active'=> ['border-l-4', 'border-cyan-400', '!border-opacity-100', 'bg-cyan-800', 'bg-opacity-30', 'text-white', 'font-medium'],
                'footer-bg' => ['bg-slate-800', 'bg-opacity-90', 'text-white', 'text-opacity-80'],
            ],
        ]
    ]
];