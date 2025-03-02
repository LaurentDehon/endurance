<!DOCTYPE html>
<html lang="fr" class="h-full">
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
    </head>

    <body class="h-full">
        @auth
            <!-- Sidebar -->
            <aside class="sidebar fixed h-full w-52 bg-gray-900/80 text-white transition-all duration-300 z-50">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-person-running text-blue-500 text-2xl"></i>
                        <span class="text-xl font-bold" x-show="expanded" x-cloak>Endurance</span>
                    </div>
                </div>

                <nav class="p-4 space-y-1">
                    <a href="{{ route('main.dashboard') }}" 
                    class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('main.dashboard') ? 'active-menu' : '' }}">
                        <i class="fas fa-house text-blue-500 text-lg w-6"></i>
                        <span x-show="expanded" x-cloak>Home</span>
                    </a>
                    
                    <a href="{{ route('calendar.index') }}" 
                    class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('calendar.index') ? 'active-menu' : '' }}">
                        <i class="fas fa-calendar-week text-green-500 text-lg w-6"></i>
                        <span x-show="expanded" x-cloak>Calendar</span>
                    </a>

                    <a href="{{ route('activities.index') }}" 
                    class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('activities.index') ? 'active-menu' : '' }}">
                        <i class="fas fa-list text-orange-500 text-lg w-6"></i>
                        <span x-show="expanded" x-cloak>Activities</span>
                    </a>
                </nav>

                <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
                    <div class="space-y-1">
                        <a href="{{ route('profile.edit') }}" 
                        class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors {{ request()->routeIs('profile.edit') ? 'active-menu' : '' }}">
                            <i class="fas fa-user text-yellow-500 text-lg w-6"></i>
                            <span x-show="expanded" x-cloak>Profile</span>
                        </a>
                        
                        <a href="#" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors">
                            <i class="fas fa-gear text-gray-400 text-lg w-6"></i>
                            <span x-show="expanded" x-cloak>Settings</span>
                        </a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors">
                                <i class="fas fa-sign-out-alt text-red-500 text-lg w-6"></i>
                                <span x-show="expanded" x-cloak>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>
        @endauth

        <!-- Main content -->
        <main class="min-h-screen pt-16 md:pt-0 ml-20">
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <!-- Success notification -->
                @if (session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                        class="fixed bottom-4 left-24 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg flex items-center space-x-2 z-20">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error notification -->
                @if ($errors->any())
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
                        class="fixed bottom-4 left-24 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg w-80 z-20">
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
    </body>
</html>