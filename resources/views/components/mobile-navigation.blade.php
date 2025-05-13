{{-- Mobile Navigation Component --}}
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
                        {{ __("navigation.menu") }}
                    </h3>
                </div>

                <!-- Main navigation -->
                <nav>
                    <div class="space-y-2">
                        <a href="{{ route('home') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('home') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.home") }}</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.dashboard") }}</span>
                        </a>
                        <a href="{{ route('calendar') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('calendar') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.calendar") }}</span>
                        </a>
                        <a href="{{ route('activities') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('activities') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.activities") }}</span>
                        </a>
                        <a href="{{ route('help') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('help') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.help") }}</span>
                        </a>
                        <a href="{{ route('contact.show') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('contact.show') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.contact") }}</span>
                        </a>
                        @if (auth()->user()->is_admin)
                        <a href="{{ route('admin') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('admin') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.admin") }}</span>
                        </a>
                        @endif
                    </div>
                </nav>
            </div>
            
            <!-- User menu with divider pushed to the bottom with margin-top:auto -->
            <div class="mt-auto p-4 border-t border-white border-opacity-20">
                <h3 class="text-xs uppercase text-slate-300 mb-4 px-3">{{ __("navigation.account") }}</h3>
                <nav>
                    <div class="space-y-2">
                        <a href="{{ route('profile') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group {{ request()->routeIs('profile.edit') ? 'border-l-4 border-cyan-400 !border-opacity-100 bg-cyan-800 bg-opacity-30 text-white font-medium' : '' }}">
                            <span>{{ __("navigation.profile") }}</span>
                        </a>
                        <a href="{{ route('settings.index') }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group">
                            <span>{{ __("navigation.settings") }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left flex items-center justify-between px-3 py-2.5 rounded-xl text-white hover:bg-cyan-800 hover:bg-opacity-50 transition-all duration-200 group">
                                <span>{{ __("navigation.logout") }}</span>
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>