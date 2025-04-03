<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        
        <title>{{ config('app.name') }}</title>

        <tallstackui:script />         
        @livewireStyles 
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('js/layout-heights.js') }}"></script>

        <style>
            /* Set default values to prevent layout shift */
            :root {
                --nav-height: 64px;
                --footer-height: 57px;
            }
            
            /* Hide content until layout heights are calculated */
            body:not(.layout-ready) .layout-dependent {
                opacity: 0;
            }
            
            body.layout-ready .layout-dependent {
                opacity: 1;
                transition: opacity 0.15s ease-in;
            }
            
            body.mobile-menu-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
                height: 100%;
            }
        </style>
    </head>

    <body class="h-full flex flex-col">
        @php
        // Récupération du thème préféré de l'utilisateur depuis la session
        $themeName = session('theme_preference', config('themes.default', 'blue'));
        
        // Vérification si le thème existe dans la configuration
        if (!array_key_exists($themeName, config('themes.themes', []))) {
            $themeName = 'blue'; // Thème par défaut si celui demandé n'existe pas
        }
        
        // Récupération des données du thème
        $theme = config('themes.themes.'.$themeName, [
            'name' => 'Blue Performance',
            'colors' => [
                'background' => ['from-indigo-900', 'via-blue-800', 'to-blue-600'],
                'text-primary' => ['text-white'],
                'text-secondary' => ['text-blue-200'],
                'button-bg' => ['bg-blue-500', 'hover:bg-blue-600'],
            ],
        ]);
        
        // Construction des classes CSS pour le background
        $bgClasses = isset($theme['colors']['background']) ? implode(' ', $theme['colors']['background']) : 'from-indigo-900 via-blue-800 to-blue-600';
        @endphp

        @auth
            <div class="min-h-[calc(100vh-var(--footer-height,0px))] bg-gradient-to-br {{ $bgClasses }}">
                <nav class="top-0 w-full" x-data="{ isMobileMenuOpen: false }">
                    <div class="mx-auto px-4">
                        <div class="flex justify-between h-16">
                            <!-- Left menu -->
                            <div class="flex items-center">
                                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2 rounded-md text-white hover:bg-white hover:bg-opacity-10 focus:outline-none">
                                    <i class="fas fa-bars text-lg"></i>
                                </button>
                                
                                <!-- Main navigation -->
                                <div class="hidden lg:ml-6 lg:flex lg:space-x-1">
                                    <a href="{{ route('home') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'border-b-2 border-white text-white' : 'text-white hover:border-b-2 hover:border-opacity-20 hover:border-white' }}">
                                        Home
                                    </a>

                                    <a href="{{ route('dashboard') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'border-b-2 border-white text-white' : 'text-white hover:border-b-2 hover:border-opacity-20 hover:border-white' }}">
                                        Dashboard
                                    </a>
                                    
                                    <a href="{{ route('calendar') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('calendar') ? 'border-b-2 border-white text-white' : 'text-white hover:border-b-2 hover:border-opacity-20 hover:border-white' }}">
                                        Calendar
                                    </a>
                                    
                                    <a href="{{ route('activities') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('activities') ? 'border-b-2 border-white text-white' : 'text-white hover:border-b-2 hover:border-opacity-20 hover:border-white' }}">
                                        Activities
                                    </a>                                

                                    @if (auth()->user()->is_admin)
                                        <a href="{{ route('admin') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin') ? 'border-b-2 border-white text-white' : 'text-white hover:border-b-2 hover:border-opacity-20 hover:border-white' }}">
                                            Admin
                                        </a>
                                    @endif      
                                    <a href="{{ route('help') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('help') ? 'border-b-2 border-white text-white' : 'text-white hover:border-b-2 hover:border-opacity-20 hover:border-white' }}">
                                        Help
                                    </a>
    
                                    <a href="{{ route('contact.show') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('contact.show') ? 'border-b-2 border-white text-white' : 'text-white hover:border-b-2 hover:border-opacity-20 hover:border-white' }}">
                                        Contact
                                    </a>                                                          
                                </div>
                            </div>

                            <!-- Right menu -->
                            <div class="flex items-center" x-data="{ open: false, themeOpen: false }">
                                <!-- Theme selector - Visible in both desktop and mobile -->
                                <div class="relative mr-4">
                                    <button @click="themeOpen = !themeOpen" class="flex items-center space-x-2 text-sm font-medium text-white hover:text-white focus:outline-none">
                                        <i class="fas fa-palette text-xl"></i>
                                        <span class="hidden md:block">{{ $theme['name'] }}</span>
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </button>
                                    
                                    <!-- Theme dropdown -->
                                    <div x-show="themeOpen" @click.away="themeOpen = false" x-cloak
                                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                        <div class="py-1">
                                            <form method="POST" action="{{ route('theme.switch') }}" id="themeForm">
                                                @csrf
                                                @foreach(config('themes.themes', []) as $key => $themeOption)
                                                    <button type="submit" name="theme" value="{{ $key }}" 
                                                        class="w-full text-left flex items-center px-4 py-2 text-sm {{ $themeName === $key ? 'bg-gray-100 text-gray-900' : 'text-gray-800 hover:bg-gray-100' }}">
                                                        <span class="w-3 h-3 mr-3 rounded-full" style="background: linear-gradient(to bottom right, {{ str_replace(['from-', 'via-', 'to-'], '', implode(', ', $themeOption['colors']['background'])) }});"></span>
                                                        {{ $themeOption['name'] }}
                                                    </button>
                                                @endforeach
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- User menu -->
                                <div class="hidden lg:block relative">
                                    <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-white hover:text-white focus:outline-none mr-8">
                                        <i class="fas fa-user-circle text-xl"></i>
                                        <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </button>

                                    <!-- User menu dropdown -->
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        class="absolute right-0 mt-2 w-auto rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                        <div class="py-1">
                                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                                                Profile
                                            </a>
                                            <a href="#" class="block px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                                                Settings
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-800 hover:bg-gray-100">
                                                    Logout
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile menu -->
                                <div class="lg:hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50" 
                                     x-show="isMobileMenuOpen" x-cloak @click.self="isMobileMenuOpen = false">
                                    <div class="absolute top-0 left-0 w-60 bg-white h-full shadow-2xl rounded-r-xl overflow-hidden flex flex-col transform transition-all duration-300"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="-translate-x-full"
                                        x-transition:enter-end="translate-x-0"
                                        x-transition:leave="transition ease-in duration-250"
                                        x-transition:leave-start="translate-x-0"
                                        x-transition:leave-end="-translate-x-full">
                                    
                                        <!-- Header with close button -->
                                        <div class="flex-shrink-0 flex items-center justify-between px-4 pt-4 pb-2 border-b border-gray-100">
                                            <span class="font-bold text-lg">Menu</span>                                        
                                            <button @click="isMobileMenuOpen = false" 
                                                    class="p-2.5 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-gray-700 transition-colors"
                                                    aria-label="Close menu">
                                                <i class="fas fa-times fa-lg"></i>
                                            </button>
                                        </div>

                                        <!-- Main menu -->
                                        <nav class="flex-1 flex flex-col h-full justify-between overflow-y-auto">
                                            <!-- Main navigation links -->
                                            <div class="space-y-1 px-4 py-6">
                                                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                    Home
                                                </a>
                                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                    Dashboard
                                                </a>
                                                <a href="{{ route('calendar') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('calendar') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                    Calendar
                                                </a>
                                                <a href="{{ route('activities') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('activities') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                    Activities
                                                </a>
                                                @if (auth()->user()->is_admin)
                                                <a href="{{ route('admin') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('admin') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                    Admin
                                                </a>
                                                @endif
                                                <a href="{{ route('help') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('help') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                    Help
                                                </a>
                                                <a href="{{ route('contact.show') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('contact.show') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                    Contact
                                                </a>
                                            </div>
                                            
                                            <!-- User menu - pushed to bottom -->
                                            <div class="border-t border-gray-100 mt-auto">
                                                <div class="space-y-1 px-4 py-6">
                                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'border-l-2 '.themeClass('accent-1', 'border-blue-400').' '.themeClass('accent-1', 'text-blue-300').' pl-5' : '' }}">
                                                        Profile
                                                    </a>
                                                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200">
                                                        Settings
                                                    </a>
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200">
                                                            Logout
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </nav>
                                    </div>
                                </div>              
                            </div>
                        </div>
                    </div>
                </nav>
            
                <!-- Main content -->
                <main class="flex-grow layout-dependent">
                    <div class="h-full">
                        <!-- Page Content -->
                        @yield('content')
                    </div>
                </main>
            </div>

            <!-- Footer -->
            <footer class="{{ themeClass('footer-bg', 'bg-white') }} border-t {{ themeClass('footer-border', 'border-gray-100') }} py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Brand and copyright in one section -->
                        <div class="flex items-center gap-2 {{ themeClass('footer-text-muted', 'text-gray-400') }} text-xs">
                            <span class="{{ themeClass('footer-text-brand', 'text-gray-700') }} font-medium">{{ config('app.name') }}</span>
                            <span>•</span>
                            <span>© {{ date('Y') }} All rights reserved</span>
                        </div>
                        
                        <!-- Powered by image -->
                        <img src="{{ asset('storage/images/powered.png') }}" alt="Powered by" class="h-5">
                        
                        <!-- Links all in one row -->
                        <div class="flex items-center gap-4 text-xs">
                            <a href="#" class="{{ themeClass('footer-text-link', 'text-gray-600 hover:text-gray-800') }} transition-colors">Terms</a>
                            <a href="#" class="{{ themeClass('footer-text-link', 'text-gray-600 hover:text-gray-800') }} transition-colors">Privacy</a>
                        </div>
                    </div>
                </div>
            </footer>
        @endauth

        @guest
            <div class="min-h-screen bg-gradient-to-br {{ $bgClasses }} text-white">
                <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-0">
                    @yield('content')
                </div>
            </div>
        @endguest
        
        <!-- Modals and other UI components -->
        <div id="training-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="modal-content bg-transparent rounded-lg mx-auto mt-5 max-w-2xl relative max-h-[95vh] overflow-y-auto">
                <div id="modal-body"></div>
            </div>
        </div>
        
        <x-ts-toast />  
        <x-ts-dialog /> 
        <x-ts-tooltip />
        
        @livewire('wire-elements-modal')
        @livewireScripts  
    </body>
</html>
<script>
    function onDragStart(e, trainingId) {
        const isCopy = e.ctrlKey;
        e.dataTransfer.setData('text/plain', JSON.stringify({
            trainingId,
            isCopy
        }));
        
        if(isCopy) {
            e.currentTarget.classList.add('dragging-copy');
        } else {
            e.currentTarget.classList.add('opacity-50');
        }
    }
    function onDragOver(e) {
        e.preventDefault();
        e.currentTarget.classList.add('bg-blue-50', 'border-blue-300');
    }

    function onDragLeave(e) {
        e.currentTarget.classList.remove('bg-blue-50', 'border-blue-300', 'dragging-copy');
    }

    function onDrop(e, newDate) {
        e.preventDefault();
        const data = JSON.parse(e.dataTransfer.getData('text/plain'));
        const trainingId = data.trainingId;
        const isCopy = data.isCopy;
        
        e.currentTarget.classList.remove('bg-blue-50', 'border-blue-300', 'dragging-copy');
        
        if(isCopy) {
            Livewire.dispatch('training-copied', {
                trainingId: parseInt(trainingId),
                newDate: newDate
            });
        } else {
            Livewire.dispatch('training-moved', {
                trainingId: parseInt(trainingId),
                newDate: newDate
            });
        }
    }

    // Toggle body class when mobile menu opens/closes
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.body;
        const menuButton = document.querySelector('.lg\\:hidden.p-2.rounded-md');
        const closeButton = document.querySelector('[aria-label="Close menu"]');
        const mobileMenu = document.querySelector('.lg\\:hidden.fixed.inset-0');
        
        if (menuButton && closeButton && mobileMenu) {
            menuButton.addEventListener('click', function() {
                body.classList.add('mobile-menu-open');
            });
            
            closeButton.addEventListener('click', function() {
                body.classList.remove('mobile-menu-open');
            });
            
            // Handle click-away on the overlay (outside the menu)
            mobileMenu.addEventListener('click', function(e) {
                // Only trigger if clicking directly on the backdrop (not on the menu itself)
                if (e.target === mobileMenu) {
                    body.classList.remove('mobile-menu-open');
                }
            });
        }
    });
</script>