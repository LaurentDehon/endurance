<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title>{{ config('app.name') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        
        <!-- Scripts -->
        @livewireStyles 
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="min-h-screen flex flex-col">
        @auth
            <div class="flex-grow bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
                <nav class="top-0 w-full" x-data="{ isMobileMenuOpen: false }">
                    <div class="mx-auto px-4">
                        <div class="flex justify-between h-16">
                            <!-- Left menu -->
                            <div class="flex items-center">
                                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2 rounded-md text-white hover:bg-opacity-10 focus:outline-none">
                                    <i class="fas fa-bars text-lg"></i>
                                </button>
                                
                                <!-- Main navigation -->
                                <div class="hidden lg:ml-6 lg:flex lg:space-x-4">
                                    <a href="{{ route('home') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
                                        Home
                                    </a>
                                    <a href="{{ route('dashboard') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
                                        Dashboard
                                    </a>                                    
                                    <a href="{{ route('calendar') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('calendar') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
                                        Calendar
                                    </a>                                    
                                    <a href="{{ route('activities') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('activities') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
                                        Activities
                                    </a>
                                    <a href="{{ route('help') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('help') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
                                        Help
                                    </a>    
                                    <a href="{{ route('contact.show') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('contact.show') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
                                        Contact
                                    </a>                                                          
                                    @if (auth()->user()->is_admin)
                                        <a href="{{ route('admin') }}" class="w-24 text-center px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin') ? 'text-white border-b-2 border-cyan-400' : 'text-slate-100 hover:border-b-2 border-cyan-500' }}">
                                            Admin
                                        </a>
                                    @endif      
                                </div>
                            </div>

                            <!-- Right menu -->
                            <div class="flex items-center gap-4" x-data="{ open: false }"> 
                                <!-- User menu -->
                                <div class="hidden lg:block relative">
                                    <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-white hover:opacity-80 transition-opacity duration-200 focus:outline-none">
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
                                        class="absolute right-0 py-1 px-2 mt-3 w-auto rounded-xl overflow-hidden shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-50 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border backdrop-blur-sm">
                                        <div class="py-1 divide-y border-white border-opacity-20">
                                            <div class="pb-1 px-3">
                                                <p class="text-xs text-cyan-200 font-medium">ACCOUNT</p>
                                            </div>
                                            <div class="pt-1">
                                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2.5 text-sm text-white hover:bg-white hover:bg-opacity-20 transition-colors rounded-lg">
                                                    <i class="fas fa-user-edit w-5 mr-3 text-cyan-200"></i>
                                                    <span>Profile</span>
                                                </a>
                                                {{-- <a href="#" class="flex items-center px-4 py-2.5 text-sm text-white hover:bg-white hover:bg-opacity-20 transition-colors rounded-lg">
                                                    <i class="fas fa-cog w-5 mr-3 text-cyan-200"></i>
                                                    <span>Settings</span>
                                                </a> --}}
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-sm text-white hover:bg-red-500 hover:bg-opacity-50 transition-colors rounded-lg">
                                                        <i class="fas fa-sign-out-alt w-5 mr-3 text-cyan-200"></i>
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
                                    <div class="absolute top-0 left-0 w-48 bg-white bg-opacity-10 border-white border-opacity-20 h-full shadow-lg rounded-r-xl overflow-hidden flex flex-col transform transition-all duration-300"
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
                                                <div class="flex justify-between items-center pb-4 mb-4 border-b border-white border-opacity-20">
                                                    <h3 class="font-bold mb-3 mt-5 text-white">
                                                        <i class="fas fa-map-marker-alt mr-2 text-amber-300"></i>
                                                        Menu
                                                    </h3>
                                                </div>

                                                <!-- Main navigation -->
                                                <nav>
                                                    <div class="space-y-3">
                                                        <a href="{{ route('home') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('home') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Home</span>
                                                        </a>
                                                        <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Dashboard</span>
                                                        </a>
                                                        <a href="{{ route('calendar') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('calendar') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Calendar</span>
                                                        </a>
                                                        <a href="{{ route('activities') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('activities') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Activities</span>
                                                        </a>
                                                        <a href="{{ route('help') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('help') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Help</span>
                                                        </a>
                                                        <a href="{{ route('contact.show') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('contact.show') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Contact</span>
                                                        </a>
                                                        @if (auth()->user()->is_admin)
                                                        <a href="{{ route('admin') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('admin') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Admin</span>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </nav>
                                            </div>
                                            
                                            <!-- User menu with divider pushed to the bottom with margin-top:auto -->
                                            <div class="mt-auto p-4 border-t border-white border-opacity-20">
                                                <h3 class="text-xs uppercase text-slate-300 mb-4 px-3">Account</h3>
                                                <nav>
                                                    <div class="space-y-3">
                                                        <a href="{{ route('profile.edit') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('profile.edit') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                                                            <span>Profile</span>
                                                        </a>
                                                        {{-- <a href="#" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group">
                                                            <span>Settings</span>
                                                        </a> --}}
                                                        <form method="POST" action="{{ route('logout') }}">
                                                            @csrf
                                                            <button type="submit" class="w-full text-left flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group">
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
            <footer class="py-4 border-t bg-slate-800 bg-opacity-90 text-white text-opacity-80 border-white border-opacity-20">
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
                            <a href="#" class="text-cyan-400 transition-colors">Terms</a>
                            <a href="#" class="text-cyan-400 transition-colors">Privacy</a>
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
                                    <a href="#" class="text-cyan-400 transition-colors">Terms</a>
                                    <a href="#" class="text-cyan-400 transition-colors">Privacy</a>
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
        <div id="workout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="modal-content bg-transparent rounded-lg mx-auto mt-5 max-w-2xl relative max-h-[95vh] overflow-y-auto">
                <div id="modal-body"></div>
            </div>
        </div>
        
        <livewire:custom-modal />
        <livewire:confirmation-modal />
        <livewire:toast />
        
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
// Fonctions améliorées pour le drag & drop
function onDragStart(e, workoutId) {
    const isCopy = e.ctrlKey || e.metaKey;
    
    // Définir les données à transférer
    e.dataTransfer.setData('text/plain', JSON.stringify({
        workoutId,
        isCopy
    }));
    
    // Définir l'effet du drag (copier ou déplacer)
    e.dataTransfer.effectAllowed = isCopy ? 'copy' : 'move';
    
    // Appliquer un retour visuel
    if(isCopy) {
        e.currentTarget.classList.add('dragging-copy');
    } else {
        e.currentTarget.classList.add('opacity-50');
    }
}

function onDragOver(e) {
    // Très important: empêcher le comportement par défaut pour permettre le drop
    e.preventDefault();
    
    // Définir l'effet de drop selon le mode (copie ou déplacement)
    if (e.dataTransfer.getData('text/plain')) {
        try {
            const data = JSON.parse(e.dataTransfer.getData('text/plain'));
            e.dataTransfer.dropEffect = data.isCopy ? 'copy' : 'move';
        } catch (error) {
            e.dataTransfer.dropEffect = 'move';
        }
    }
    
    // Ajouter une classe pour le retour visuel
    e.currentTarget.classList.add('drag-over');
}

function onDragLeave(e) {
    // Supprimer la classe de retour visuel
    e.currentTarget.classList.remove('drag-over');
}

function onDrop(e, newDate) {
    // Empêcher le comportement par défaut
    e.preventDefault();
    e.stopPropagation();
    
    try {
        // Extraire les données
        const data = JSON.parse(e.dataTransfer.getData('text/plain'));
        const workoutId = data.workoutId;
        const isCopy = data.isCopy;
        
        // Supprimer le retour visuel
        e.currentTarget.classList.remove('drag-over');
        
        // Trouver l'élément original pour lui retirer la classe d'opacité
        document.querySelectorAll('.opacity-50, .dragging-copy').forEach(el => {
            el.classList.remove('opacity-50', 'dragging-copy');
        });
        
        // Déclencher l'événement Livewire approprié
        if(isCopy) {
            Livewire.dispatch('workout-copied', {
                workoutId: parseInt(workoutId),
                newDate: newDate
            });
        } else {
            Livewire.dispatch('workout-moved', {
                workoutId: parseInt(workoutId),
                newDate: newDate
            });
        }
    } catch (error) {
        console.error('Error during drop:', error);
    }
}
</script>