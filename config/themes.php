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
                'progress-bg'=> ['bg-gray-800'],                
                'border-accent'=> ['border-amber-300'],
                'bg-accent'=> ['bg-amber-200'],
                'check-bg'=> ['from-cyan-700', 'to-blue-500'],
                'check-ring'=> ['ring-cyan-500'],
                'divider'=> ['border-white', 'border-opacity-20'],
                'nav'=> ['text-slate-100', 'border-cyan-500'],
                'nav-hover'=> ['bg-cyan-800', 'bg-opacity-20', 'text-cyan-200'],
                'nav-active'=> ['border-cyan-400', 'text-white'],
                'button' => ['bg-cyan-700', 'hover:bg-cyan-600','text-white'],
                'button-accent' => ['bg-amber-600', 'hover:bg-amber-500', 'text-white'],
                'button-danger' => ['bg-red-600', 'hover:bg-red-500','text-white'],
                'input' => ['bg-slate-700', 'bg-opacity-60', 'text-white', 'placeholder-gray-400', 'border-slate-600', 'border-opacity-50'],
                'mobile-bg'=> ['from-slate-900', 'via-blue-900', 'to-slate-800'],
                'mobile-nav'=> ['text-white', 'hover:bg-cyan-800', 'hover:bg-opacity-50', 'hover:text-white'],
                'mobile-nav-active'=> ['border-l-4', 'border-cyan-400', '!border-opacity-100', 'bg-cyan-800', 'bg-opacity-30', 'text-white', 'font-medium'],
                'modal-bg'=> ['bg-slate-900', 'bg-opacity-90', 'border-white', 'border-opacity-20'],
                'footer-bg' => ['bg-slate-800', 'bg-opacity-90', 'text-white', 'text-opacity-80', 'border-white', 'border-opacity-20'],
                'toast-success' => ['bg-green-100', 'text-green-800', 'shadow-green-200/20'],
                'toast-error' => ['bg-red-100', 'text-red-800', 'shadow-red-200/20'],
                'toast-warning' => ['bg-yellow-100', 'text-yellow-800', 'shadow-yellow-200/20'],
                'toast-info' => ['bg-blue-100', 'text-blue-800', 'shadow-blue-200/20'],
                'toast-close' => ['text-gray-500', 'hover:text-gray-700'],
            ],
        ]
    ]
];