<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        
        <title>Endurance</title>

        <tallstackui:script />         
        @livewireStyles 
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="h-full">
        @auth
            <nav class="top-0 w-full bg-white shadow-sm z-10" x-data="{ isMobileMenuOpen: false }">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left menu -->
                        <div class="flex items-center">
                            <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="lg:hidden p-2 rounded-md text-gray-600 hover:text-gray-800 hover:bg-gray-100 focus:outline-none">
                                <i class="fas fa-bars text-lg"></i>
                            </button>
                            
                            <div class="flex-shrink-0 flex items-center ml-2">
                                <i class="fas fa-person-running text-blue-500 text-2xl"></i>
                                <span class="ml-2 text-xl font-bold hidden md:block">Endurance</span>
                            </div>
                            
                            <!-- Main navigation -->
                            <div class="hidden lg:ml-6 lg:flex lg:space-x-1">
                                <a href="{{ route('home') }}" class="flex-1 flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'bg-gray-100 text-indigo-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-house mr-2"></i>
                                    Home
                                </a>

                                <a href="{{ route('dashboard') }}" class="flex-1 flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-house mr-2"></i>
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('calendar') }}" class="flex-1 flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('calendar') ? 'bg-gray-100 text-purple-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-calendar-week mr-2"></i>
                                    Calendar
                                </a>
                                
                                <a href="{{ route('activities') }}" class="flex-1 flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('activities') ? 'bg-gray-100 text-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-list mr-2"></i>
                                    Activities
                                </a>                                

                                @if (auth()->user()->is_admin)
                                    <a href="{{ route('admin') }}" class="flex-1 flex items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('admin') ? 'bg-gray-100 text-red-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <i class="fas fa-unlock mr-2"></i>
                                        Admin
                                    </a>
                                @endif      
                                <a href="{{ route('help') }}" class="lg:flex hidden  mr-2 flex-1 items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('help') ? 'bg-gray-100 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-circle-question mr-2"></i>
                                    <span class="hidden lg:inline">Help</span>
                                </a>
    
                                <a href="{{ route('contact.show') }}" class="lg:flex hidden mr-6 flex-1 items-center justify-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('contact.show') ? 'bg-gray-100 text-pink-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-envelope-open mr-2"></i>
                                    <span class="hidden lg:inline">Contact</span>
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
                                 class="origin-top-right absolute right-4 mt-36 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
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
                                @click.away="isMobileMenuOpen = false">

                                <div class="absolute top-0 left-0 w-64 bg-white h-full shadow-2xl rounded-r-xl flex flex-col transform transition-all duration-300"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="-translate-x-full"
                                    x-transition:enter-end="translate-x-0"
                                    x-transition:leave="transition ease-in duration-250"
                                    x-transition:leave-start="translate-x-0"
                                    x-transition:leave-end="-translate-x-full">
                                
                                    <!-- Header with close button -->
                                    <div class="flex items-center justify-between px-4 pt-4 pb-2 border-b border-gray-100">
                                        <div class="flex items-center gap-2 text-indigo-600">
                                            <i class="fas fa-running text-lg"></i>
                                            <span class="font-bold text-lg">Menu</span>
                                        </div>
                                        <button @click="isMobileMenuOpen = false" 
                                                class="p-2.5 hover:bg-gray-100 rounded-lg text-gray-500 hover:text-gray-700 transition-colors"
                                                aria-label="Close menu">
                                            <i class="fas fa-times fa-lg"></i>
                                        </button>
                                    </div>

                                    <!-- Main menu -->
                                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                                        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-indigo-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-house text-indigo-500 w-5 text-center"></i>
                                            <span>Home</span>
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-blue-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-chart-line text-blue-500 w-5 text-center"></i>
                                            <span>Dashboard</span>
                                        </a>
                                        <a href="{{ route('calendar') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-purple-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-calendar-week text-purple-500 w-5 text-center"></i>
                                            <span>Calendar</span>
                                        </a>
                                        <a href="{{ route('activities') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-green-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-list text-green-500 w-5 text-center"></i>
                                            <span>Activities</span>
                                        </a>
                                        @if (auth()->user()->is_admin)
                                        <a href="{{ route('admin') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-unlock text-red-500 w-5 text-center"></i>
                                            <span>Admin</span>
                                        </a>
                                        @endif
                                        <a href="{{ route('help') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-orange-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-circle-question text-orange-500 w-5 text-center"></i>
                                            <span>Help</span>
                                        </a>
                                        <a href="{{ route('contact.show') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-pink-50 rounded-xl transition-all duration-200">
                                            <i class="fas fa-envelope-open text-pink-500 w-5 text-center"></i>
                                            <span>Contact</span>
                                        </a>
                                    </nav>

                                    <!-- User menu -->
                                    <div class="mt-auto border-t border-gray-100">
                                        <div class="px-4 py-4 space-y-2">
                                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-xl transition-all duration-200">
                                                <i class="fas fa-user text-yellow-500 w-5 text-center"></i>
                                                <span>Profile</span>
                                            </a>
                                            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-gray-50 rounded-xl transition-all duration-200">
                                                <i class="fas fa-gear text-gray-400 w-5 text-center"></i>
                                                <span>Settings</span>
                                            </a>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-gray-600 hover:bg-red-50 rounded-xl transition-all duration-200">
                                                    <i class="fas fa-sign-out-alt text-red-500 w-5 text-center"></i>
                                                    <span>Logout</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>              
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Main content -->
        <main class="min-h-screen">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Page Content -->
                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-100 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="flex flex-col md:flex-row justify-between gap-8">
                    <!-- Brand Section -->
                    <div class="space-y-2 max-w-xs">
                        <span class="text-gray-900 font-medium tracking-tight">Endurance</span>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Training plan creation and tracking solution
                        </p>
                    </div>
                    <img src="{{ asset('storage/images/powered.png') }}" alt="Powered by" class="h-7 mt-4">
        
                    <!-- Links Container -->
                    <div class="flex flex-col sm:flex-row gap-12">
                        <!-- Legal Links -->
                        <div class="space-y-3">
                            <h5 class="text-gray-500 text-sm font-medium">Legal</h5>
                            <ul class="space-y-2.5">
                                <li><a href="#" class="text-gray-600 hover:text-gray-800 text-sm transition-colors">Terms of Use</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-gray-800 text-sm transition-colors">Privacy Policy</a></li>
                            </ul>
                        </div>
        
                        <!-- Support Links -->
                        <div class="space-y-3">
                            <h5 class="text-gray-500 text-sm font-medium">Support</h5>
                            <ul class="space-y-2.5">
                                <li><a href="{{ route('contact.show') }}" class="text-gray-600 hover:text-gray-800 text-sm transition-colors">Technical Support</a></li>
                                <li><a href="{{ route('help') }}" class="text-gray-600 hover:text-gray-800 text-sm transition-colors">Documentation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
        
                <!-- Copyright -->
                <div class="border-t border-gray-100 mt-12 pt-6">
                    <p class="text-gray-400 text-xs text-center">
                        © {{ date('Y') }} Endurance. All rights reserved
                    </p>
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
</script>