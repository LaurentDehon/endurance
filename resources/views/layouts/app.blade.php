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

    <body class="min-h-screen flex flex-col">
        @php
            // Get the theme preference from session or use the default theme
            $themeName = session('theme_preference', config('themes.default', 'blue'));
        @endphp

        @auth
            <div class="flex-grow bg-gradient-to-br {{ themeClass('background') }}">
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
                                <div class="mr-20 relative lg:mr-2">
                                    <button @click="themeOpen = !themeOpen" class="flex items-center space-x-2 text-sm font-medium {{ themeClass('text-1') }} hover:opacity-80 transition-opacity duration-200 focus:outline-none">
                                        <i class="fas fa-palette text-xl"></i>
                                        <span class="hidden xl:block">{{ $theme['name'] }}</span>
                                        <i class="hidden xl:block fas fa-chevron-down text-xs ml-1 opacity-70 transition-transform duration-200" :class="{'rotate-180': themeOpen}"></i>
                                    </button>
                                    
                                    <!-- Theme dropdown -->
                                    <div x-show="themeOpen" @click.away="themeOpen = false" x-cloak
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 mt-3 w-60 rounded-xl overflow-hidden shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 {{ themeClass('card') }} backdrop-blur-sm">
                                        <div class="py-2 divide-y {{ themeClass('divider') }}">
                                            <div class="pb-1 px-3">
                                                <p class="text-xs {{ themeClass('text-2') }} font-medium">APPEARANCE</p>
                                            </div>
                                            <form method="POST" action="{{ route('theme.switch') }}" id="themeForm" class="py-1">
                                                @csrf
                                                <div class="space-y-0.5">
                                                @foreach(config('themes.themes', []) as $key => $themeOption)
                                                    <button type="submit" name="theme" value="{{ $key }}" 
                                                        class="w-full text-left flex items-center px-4 py-2.5 text-sm {{ $themeName === $key ? themeClass('button') . ' bg-opacity-20' : themeClass('text-1') . ' hover:bg-white hover:bg-opacity-10' }} transition-colors">
                                                        <span class="w-4 h-4 mr-3 rounded-full {{ $themeName === $key ? 'bg-gradient-to-br ' . themeClass('check-bg') .' ring-2 ' . themeClass('check-ring') . ' ring-opacity-30' : 'bg-opacity-20 bg-white' }} flex items-center justify-center">
                                                            @if($themeName === $key)
                                                            <i class="fas fa-check text-[10px] text-white"></i>
                                                            @endif
                                                        </span>
                                                        <span class="{{ $themeName === $key ? 'font-medium ' . themeClass('text-1') : themeClass('text-2') }}">
                                                            {{ $themeOption['name'] }}
                                                        </span>
                                                    </button>
                                                @endforeach
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- User menu -->
                                <div class="hidden lg:block relative">
                                    <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium {{ themeClass('text-1') }} hover:opacity-80 transition-opacity duration-200 focus:outline-none">
                                        <i class="fas fa-user-circle text-xl"></i>
                                        <span class="hidden xl:block">{{ Auth::user()->name }}</span>
                                        <i class="fas fa-chevron-down text-xs ml-1 opacity-70 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                                    </button>

                                    <!-- User menu dropdown -->
                                    <div x-show="open" @click.away="open = false" x-cloak
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="transform opacity-0 scale-95"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-95"
                                        class="absolute right-0 mt-3 w-auto rounded-xl overflow-hidden shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 {{ themeClass('card') }} backdrop-blur-sm">
                                        <div class="py-2 divide-y {{ themeClass('divider') }}">
                                            <div class="pb-1 px-3">
                                                <p class="text-xs {{ themeClass('text-2') }} font-medium">ACCOUNT</p>
                                            </div>
                                            <div class="pt-1">
                                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm {{ themeClass('text-1') }} hover:bg-white hover:bg-opacity-10 transition-colors">
                                                    <i class="fas fa-user-edit w-5 mr-3 {{ themeClass('text-2') }}"></i>
                                                    <span>Profile</span>
                                                </a>
                                                <a href="#" class="flex items-center px-4 py-2.5 text-sm {{ themeClass('text-1') }} hover:bg-white hover:bg-opacity-10 transition-colors">
                                                    <i class="fas fa-cog w-5 mr-3 {{ themeClass('text-2') }}"></i>
                                                    <span>Settings</span>
                                                </a>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-sm {{ themeClass('button-danger') }} bg-opacity-5 hover:bg-opacity-20 transition-colors">
                                                        <i class="fas fa-sign-out-alt w-5 mr-3 {{ themeClass('text-2') }}"></i>
                                                        <span>Logout</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile menu -->
                                <div class="lg:hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50" 
                                     x-show="isMobileMenuOpen" x-cloak @click.self="isMobileMenuOpen = false">
                                    <div class="absolute top-0 left-0 w-48 {{ themeClass('card') }} h-full shadow-2xl rounded-r-xl overflow-hidden flex flex-col transform transition-all duration-300"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="-translate-x-full"
                                        x-transition:enter-end="translate-x-0"
                                        x-transition:leave="transition ease-in duration-250"
                                        x-transition:leave-start="translate-x-0"
                                        x-transition:leave-end="-translate-x-full">
                                    
                                        <!-- Header and content with navigation and user menu at the bottom -->
                                        <div class="flex flex-col h-full">
                                            <!-- Header and main navigation -->
                                            <div class="p-4">
                                                <!-- Header -->
                                                <div class="flex justify-between items-center pb-4 mb-4 border-b {{ themeClass('divider') }}">
                                                    <h3 class="font-bold mb-3 mt-5 {{ themeClass('text-1') }}">
                                                        <i class="fas fa-map-marker-alt mr-2 {{ themeClass('text-accent') }}"></i>
                                                        Menu
                                                    </h3>
                                                </div>

                                                <!-- Main navigation -->
                                                <nav>
                                                    <div class="space-y-4">
                                                        <a href="{{ route('home') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('home') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Home</span>
                                                        </a>
                                                        <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('dashboard') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Dashboard</span>
                                                        </a>
                                                        <a href="{{ route('calendar') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('calendar') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Calendar</span>
                                                        </a>
                                                        <a href="{{ route('activities') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('activities') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Activities</span>
                                                        </a>
                                                        <a href="{{ route('help') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('help') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Help</span>
                                                        </a>
                                                        <a href="{{ route('contact.show') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('contact.show') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Contact</span>
                                                        </a>
                                                        @if (auth()->user()->is_admin)
                                                        <a href="{{ route('admin') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('admin') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Admin</span>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </nav>
                                            </div>
                                            
                                            <!-- User menu with divider pushed to the bottom with margin-top:auto -->
                                            <div class="mt-auto p-4 border-t {{ themeClass('divider') }}">
                                                <h3 class="text-xs uppercase {{ themeClass('text-3') }} mb-4 px-3">Account</h3>
                                                <nav>
                                                    <div class="space-y-4">
                                                        <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group {{ request()->routeIs('profile.edit') ? themeClass('mobile-nav-active') : '' }}">
                                                            <span>Profile</span>
                                                        </a>
                                                        <a href="#" class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group">
                                                            <span>Settings</span>
                                                        </a>
                                                        <form method="POST" action="{{ route('logout') }}">
                                                            @csrf
                                                            <button type="submit" class="w-full text-left flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} hover:{{ themeClass('nav-hover') }} transition-all duration-200 group">
                                                                <span>Logout</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </nav>
                                            </div>
                                        </div>
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
            <footer class="py-4 {{ themeClass('footer-bg') }} border-t">
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
        
        <livewire:custom-modal />
        <x-ts-toast />
        <x-ts-tooltip />
        
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

function onDragStart(e, trainingId) {
    const isCopy = e.ctrlKey;
    // Store the training ID and copy status in the drag data
    e.dataTransfer.setData('text/plain', JSON.stringify({
        trainingId,
        isCopy
    }));
    
    // Apply visual feedback based on operation type (copy or move)
    if(isCopy) {
        e.currentTarget.classList.add('dragging-copy');
    } else {
        e.currentTarget.classList.add('opacity-50');
    }
}

function onDragOver(e) {
    e.preventDefault(); // Allow dropping
    e.currentTarget.classList.add('drag-over'); // Visual feedback for potential drop
    // Apply theme-specific classes using theme helper in the inline style
    e.currentTarget.setAttribute('data-drag-hovering', 'true');
}

function onDragLeave(e) {
    // Remove visual feedback when dragging leaves the drop target
    e.currentTarget.classList.remove('drag-over');
    e.currentTarget.removeAttribute('data-drag-hovering');
    e.currentTarget.classList.remove('dragging-copy');
}

function onDrop(e, newDate) {
    e.preventDefault();
    // Extract data from the drag operation
    const data = JSON.parse(e.dataTransfer.getData('text/plain'));
    const trainingId = data.trainingId;
    const isCopy = data.isCopy;
    
    // Remove visual feedback
    e.currentTarget.classList.remove('drag-over');
    e.currentTarget.removeAttribute('data-drag-hovering');
    e.currentTarget.classList.remove('dragging-copy');
    
    // Dispatch appropriate Livewire event based on operation type
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
</script>