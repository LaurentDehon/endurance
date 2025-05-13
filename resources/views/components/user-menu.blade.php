<!-- User menu component -->
<div class="hidden lg:block relative" x-data="{ open: false }">
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
                    <span>{{ __("navigation.profile") }}</span>
                </a>
                <a href="{{ route('settings.index') }}" class="flex items-center px-4 py-2.5 text-sm text-white hover:bg-white hover:bg-opacity-20 transition-colors rounded-lg">
                    <i class="fas fa-cog w-5 mr-3 text-cyan-200"></i>
                    <span>{{ __("navigation.settings") }}</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-sm text-white hover:bg-red-500 hover:bg-opacity-50 transition-colors rounded-lg">
                        <i class="fas fa-sign-out-alt w-5 mr-3 text-cyan-200"></i>
                        <span>{{ __("navigation.logout") }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>