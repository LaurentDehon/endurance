<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <script src="{{ asset('js/script.js') }}" defer></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
        
        <title>Endurance</title>
        @livewireStyles
    </head>

    <body class="h-full">
        @auth
            <!-- Barre de navigation supérieure -->
            <nav class="fixed top-0 w-full bg-white shadow-sm z-50">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Partie gauche -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 flex items-center">
                                <i class="fas fa-person-running text-blue-500 text-2xl"></i>
                                <span class="ml-2 text-xl font-bold hidden md:block">Endurance</span>
                            </div>
                            
                            <!-- Navigation principale -->
                            <div class="hidden md:ml-6 md:flex md:space-x-4">
                                <a href="{{ route('main.dashboard') }}" 
                                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('main.dashboard') ? 'bg-gray-100 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-house mr-2"></i>
                                    Home
                                </a>
                                
                                <a href="{{ route('calendar.index') }}" 
                                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('calendar.index') ? 'bg-gray-100 text-green-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-calendar-week mr-2"></i>
                                    Calendar
                                </a>
                                
                                <a href="{{ route('activities.index') }}" 
                                class="inline-flex items-center px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('activities.index') ? 'bg-gray-100 text-orange-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                    <i class="fas fa-list mr-2"></i>
                                    Activities
                                </a>
                            </div>
                        </div>

                        <!-- Partie droite (menu utilisateur) -->
                        <div class="flex items-center" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center space-x-2 text-sm font-medium text-gray-600 hover:text-gray-900 focus:outline-none">
                                <i class="fas fa-user-circle text-xl"></i>
                                <span class="hidden md:block">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- Menu déroulant -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 class="origin-top-right absolute right-4 mt-36 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 x-cloak>
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-user mr-2 text-yellow-500"></i>Profile
                                    </a>
                                    <a href="#" 
                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-gear mr-2 text-gray-400"></i>Settings
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2 text-red-500"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        @endauth

        <!-- Main content -->
        <main class="min-h-screen pt-16">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Success notification -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                        class="fixed top-20 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 z-20">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error notification -->
                @if ($errors->any())
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                        class="fixed top-20 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg w-80 z-20">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-exclamation-triangle mt-1"></i>
                            <div>
                                <h5 class="font-bold">Errors</h5>
                                <ul class="list-disc pl-5 text-sm mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

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
                                <li><a href="mailto:support@endurance.fr" class="text-gray-600 hover:text-gray-800 text-sm transition-colors">Technical Support</a></li>
                                <li><a href="#" class="text-gray-600 hover:text-gray-800 text-sm transition-colors">Documentation</a></li>
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
        @livewireScripts
    </body>
</html>