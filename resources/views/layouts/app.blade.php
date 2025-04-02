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

        <style>
            body.mobile-menu-open {
                overflow: hidden;
                position: fixed;
                width: 100%;
                height: 100%;
            }
            
            .active-menu-item {
                position: relative;
            }
            
            .active-menu-item::after {
                content: '';
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background-color: rgba(0, 0, 0, 0.05);
                pointer-events: none;
                border-radius: 0.5rem;
            }
        </style>
    </head>

    <body class="h-full flex flex-col">
        @auth
            <nav class="top-0 w-full" x-data="{ isMobileMenuOpen: false }">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left menu -->
                        <div class="flex items-center">
                            <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                            
                            <!-- Main navigation -->
                            <div class="hidden lg:ml-6 lg:flex lg:space-x-2">
                                <a href="{{ route('home') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'border-b-2 border-teal-600 text-teal-600' : 'text-gray-600 hover:border-b-2 hover:border-gray-400' }}">
                                    Home
                                </a>

                                <a href="{{ route('dashboard') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600 hover:border-b-2 hover:border-gray-400' }}">
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('calendar') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('calendar') ? 'border-b-2 border-purple-600 text-purple-600' : 'text-gray-600 hover:border-b-2 hover:border-gray-400' }}">
                                    Calendar
                                </a>
                                
                                <a href="{{ route('activities') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('activities') ? 'border-b-2 border-green-600 text-green-600' : 'text-gray-600 hover:border-b-2 hover:border-gray-400' }}">
                                    Activities
                                </a>                                

                                @if (auth()->user()->is_admin)
                                    <a href="{{ route('admin') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin') ? 'border-b-2 border-red-600 text-red-600' : 'text-gray-600 hover:border-b-2 hover:border-gray-400' }}">
                                        Admin
                                    </a>
                                @endif      
                                <a href="{{ route('help') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('help') ? 'border-b-2 border-orange-600 text-orange-600' : 'text-gray-600 hover:border-b-2 hover:border-gray-400' }}">
                                    Help
                                </a>
    
                                <a href="{{ route('contact.show') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('contact.show') ? 'border-b-2 border-pink-600 text-pink-600' : 'text-gray-600 hover:border-b-2 hover:border-gray-400' }}">
                                    Contact
                                </a>                                                          
                            </div>
                        </div>

                        <!-- Right menu -->
                        <div class="flex items-center" x-data="{ open: false }">                            
                            <button @click="open = !open" class="hidden lg:flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none mr-8">
                                <i class="fas fa-user-circle text-xl"></i>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- User menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 class="origin-top-right absolute right-4 mt-36 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2 text-yellow-500"></i>Profile
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-gear mr-2 text-gray-400"></i>Settings
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2 text-red-500"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- Mobile menu -->
                            <div class="lg:hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50" 
                                x-show="isMobileMenuOpen" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                x-cloak
                                @click.self="isMobileMenuOpen = false">

                                <div class="absolute top-0 left-0 w-48 bg-white h-full shadow-2xl rounded-r-xl overflow-hidden flex flex-col transform transition-all duration-300"
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
                                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'border-l-4 border-teal-500 text-teal-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                Home
                                            </a>
                                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'border-l-4 border-blue-500 text-blue-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                Dashboard
                                            </a>
                                            <a href="{{ route('calendar') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('calendar') ? 'border-l-4 border-purple-500 text-purple-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                Calendar
                                            </a>
                                            <a href="{{ route('activities') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('activities') ? 'border-l-4 border-green-500 text-green-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                Activities
                                            </a>
                                            @if (auth()->user()->is_admin)
                                            <a href="{{ route('admin') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('admin') ? 'border-l-4 border-red-500 text-red-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                Admin
                                            </a>
                                            @endif
                                            <a href="{{ route('help') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('help') ? 'border-l-4 border-orange-500 text-orange-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                Help
                                            </a>
                                            <a href="{{ route('contact.show') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('contact.show') ? 'border-l-4 border-pink-500 text-pink-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                Contact
                                            </a>
                                        </div>
                                        
                                        <!-- User menu - pushed to bottom -->
                                        <div class="border-t border-gray-100 mt-auto">
                                            <div class="space-y-1 px-4 py-6">
                                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 {{ request()->routeIs('profile.edit') ? 'border-l-4 border-yellow-500 text-yellow-700 pl-5 active-menu-item' : 'hover:border-l-4 hover:border-gray-300 hover:pl-5' }}">
                                                    Profile
                                                </a>
                                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 hover:border-l-4 hover:border-gray-300 hover:pl-5">
                                                    Settings
                                                </a>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-gray-600 rounded-lg transition-all duration-200 hover:border-l-4 hover:border-red-300 hover:pl-5">
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
        @endauth

        <!-- Main content -->
        <main class="flex-grow">
            <div class="h-full">
                <!-- Page Content -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 py-4">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <!-- Brand and copyright in one section -->
                    <div class="flex items-center gap-2 text-gray-400 text-xs">
                        <span class="text-gray-700 font-medium">{{ config('app.name') }}</span>
                        <span>•</span>
                        <span>© {{ date('Y') }} All rights reserved</span>
                    </div>
                    
                    <!-- Powered by image -->
                    <img src="{{ asset('storage/images/powered.png') }}" alt="Powered by" class="h-5">
                    
                    <!-- Links all in one row -->
                    <div class="flex items-center gap-4 text-xs">
                        <a href="#" class="text-gray-600 hover:text-gray-800 transition-colors">Terms</a>
                        <a href="#" class="text-gray-600 hover:text-gray-800 transition-colors">Privacy</a>
                    </div>
                </div>
            </div>
        </footer>
        
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