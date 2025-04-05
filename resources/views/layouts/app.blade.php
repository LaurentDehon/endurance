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
    </head>

    <body class="h-full flex flex-col">
        @php
            // Get the theme preference from session or use the default theme
            $themeName = session('theme_preference', config('themes.default', 'blue'));
        @endphp

        @auth
            <div class="min-h-[calc(100vh-var(--footer-height,0px))] bg-gradient-to-br {{ themeClass('background') }}">
                <nav class="top-0 w-full" x-data="{ isMobileMenuOpen: false }">
                    <div class="mx-auto px-4">
                        <div class="flex justify-between h-16">
                            <!-- Left menu -->
                            <div class="flex items-center">
                                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2 rounded-md {{ themeClass('text-1') }} hover:bg-opacity-10 focus:outline-none">
                                    <i class="fas fa-bars text-lg"></i>
                                </button>
                                
                                <!-- Main navigation -->
                                <div class="hidden lg:ml-6 lg:flex lg:space-x-4">
                                    <a href="{{ route('home') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'border-b-2 ' . themeClass('nav-active') : 'hover:border-b-2 ' . themeClass('nav') }}">
                                        Home
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'border-b-2 ' . themeClass('nav-active') : 'hover:border-b-2 ' . themeClass('nav') }}">
                                        Dashboard
                                    </a>                                    
                                    <a href="{{ route('calendar') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('calendar') ? 'border-b-2 ' . themeClass('nav-active') : 'hover:border-b-2 ' . themeClass('nav') }}">
                                        Calendar
                                    </a>                                    
                                    <a href="{{ route('activities') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('activities') ? 'border-b-2 ' . themeClass('nav-active') : 'hover:border-b-2 ' . themeClass('nav') }}">
                                        Activities
                                    </a>
                                    <a href="{{ route('help') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('help') ? 'border-b-2 ' . themeClass('nav-active') : 'hover:border-b-2 ' . themeClass('nav') }}">
                                        Help
                                    </a>    
                                    <a href="{{ route('contact.show') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('contact.show') ? 'border-b-2 ' . themeClass('nav-active') : 'hover:border-b-2 ' . themeClass('nav') }}">
                                        Contact
                                    </a>                                                          
                                    @if (auth()->user()->is_admin)
                                        <a href="{{ route('admin') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin') ? 'border-b-2 ' . themeClass('nav-active') : 'hover:border-b-2 ' . themeClass('nav') }}">
                                            Admin
                                        </a>
                                    @endif      
                                </div>
                            </div>

                            <!-- Right menu -->
                            <div class="flex items-center gap-4" x-data="{ open: false, themeOpen: false }">
                                <!-- Theme selector -->
                                <div class="relative mr-4">
                                    <button @click="themeOpen = !themeOpen" class="flex items-center space-x-2 text-sm font-medium {{ themeClass('text-1') }} focus:outline-none">
                                        <i class="fas fa-palette text-xl"></i>
                                        <span class="hidden xl:block">{{ $theme['name'] }}</span>
                                    </button>
                                    
                                    <!-- Theme dropdown -->
                                    <div x-show="themeOpen" @click.away="themeOpen = false" x-cloak
                                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg  focus:outline-none z-50">
                                        <div>
                                            <form method="POST" action="{{ route('theme.switch') }}" id="themeForm">
                                                @csrf
                                                @foreach(config('themes.themes', []) as $key => $themeOption)
                                                    <button type="submit" name="theme" value="{{ $key }}" 
                                                        class="w-full text-left flex items-center px-4 py-2 text-sm {{ $themeName === $key ? 'bg-gray-100 '.themeClass('text-link') : 'text-gray-700 hover:bg-gray-200' }} transition-colors">
                                                        <span class="w-3 h-3 mr-3 rounded-full" ></span>
                                                        {{ $themeOption['name'] }}
                                                    </button>
                                                @endforeach
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- User menu -->
                                <div class="hidden lg:block relative">
                                    <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium {{ themeClass('text-1') }} focus:outline-none mr-8">
                                        <i class="fas fa-user-circle text-xl"></i>
                                        <span class="hidden xl:block">{{ Auth::user()->name }}</span>
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
                                    <div class="absolute top-0 left-0 w-60 bg-gradient-to-bl {{ themeClass('mobile-bg') }} h-full shadow-2xl rounded-r-xl overflow-hidden flex flex-col transform transition-all duration-300"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="-translate-x-full"
                                        x-transition:enter-end="translate-x-0"
                                        x-transition:leave="transition ease-in duration-250"
                                        x-transition:leave-start="translate-x-0"
                                        x-transition:leave-end="-translate-x-full">
                                    
                                        <!-- Header with close button -->
                                        <div class="flex-shrink-0 flex items-center justify-between px-4 pt-4 pb-2 border-b border-gray-100">
                                            <span class="font-bold text-lg {{ themeClass('text-1') }}">Menu</span>                                        
                                            <button @click="isMobileMenuOpen = false" aria-label="Close menu">
                                                <i class="fas fa-times fa-lg {{ themeClass('text-1') }}"></i>
                                            </button>
                                        </div>

                                        <!-- Main navigation -->
                                        <nav class="flex-1 flex flex-col h-full justify-between overflow-y-auto">
                                            <div class="space-y-2 px-4 py-3">
                                                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('home') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                    Home
                                                </a>
                                                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('dashboard') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                    Dashboard
                                                </a>
                                                <a href="{{ route('calendar') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('calendar') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                    Calendar
                                                </a>
                                                <a href="{{ route('activities') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('activities') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                    Activities
                                                </a>
                                                @if (auth()->user()->is_admin)
                                                <a href="{{ route('admin') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('admin') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                    Admin
                                                </a>
                                                @endif
                                                <a href="{{ route('help') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('help') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                    Help
                                                </a>
                                                <a href="{{ route('contact.show') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('contact.show') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                    Contact
                                                </a>
                                            </div>
                                            
                                            <!-- User menu -->
                                            <div class="border-t border-gray-100 mt-auto">
                                                <div class="space-y-2 px-4 py-3">
                                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }} {{ request()->routeIs('profile.edit') ? themeClass('mobile-nav-active') . ' pl-5' : '' }}">
                                                        Profile
                                                    </a>
                                                    <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }}">
                                                        Settings
                                                    </a>
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 {{ themeClass('mobile-nav') }}">
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
                        @yield('content')
                    </div>
                </main>
            </div>

            <!-- Footer -->
            <footer class="py-4 {{ themeClass('footer-bg') }}">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <!-- Desktop layout -->
                        <div class="hidden md:flex items-center gap-2 text-xs">
                            <span class="font-medium">{{ config('app.name') }}</span>
                            <span>•</span>
                            <span>© {{ date('Y') }} All rights reserved</span>
                        </div>
                        
                        <img src="{{ asset('storage/images/powered.png') }}" alt="Powered by" class="hidden md:block h-5">
                        
                        <div class="hidden md:flex items-center gap-4 text-xs">
                            <a href="#" class="{{ themeClass('text-link') }} transition-colors">Terms</a>
                            <a href="#" class="{{ themeClass('text-link') }} transition-colors">Privacy</a>
                        </div>
                        
                        <!-- Mobile layout -->
                        <div class="w-full md:hidden flex flex-col items-center gap-4">
                            <div class="w-full flex justify-between items-center">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="font-medium">{{ config('app.name') }}</span>
                                    <span>•</span>
                                    <span>© {{ date('Y') }}</span>
                                </div>
                                
                                <div class="flex items-center gap-4 text-xs">
                                    <a href="#" class="{{ themeClass('text-link') }} transition-colors">Terms</a>
                                    <a href="#" class="{{ themeClass('text-link') }} transition-colors">Privacy</a>
                                </div>
                            </div>
                            
                            <img src="{{ asset('storage/images/powered.png') }}" alt="Powered by" class="h-5 my-2">
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