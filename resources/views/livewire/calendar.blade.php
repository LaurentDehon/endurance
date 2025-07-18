<?php
    use App\http\Helpers\Helpers; 
    use Carbon\Carbon;
?>    

<div class="mx-auto p-2 sm:p-4 overflow-y-scroll relative" id="calendar-container"
    x-data="{ contentLoaded: false, yearChanging: false }" 
    x-init="setTimeout(() => { contentLoaded = true; }, 500)"
    @year-navigation-start.window="yearChanging = true; contentLoaded = false"
    @year-navigation-end.window="setTimeout(() => { yearChanging = false; contentLoaded = true; }, 500)">
    <!-- Fixed gradient background covering the entire page -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 -z-10"></div>

    <!-- Page loading overlay -->
    <div 
        x-show="!contentLoaded" 
        x-transition:leave="transition-opacity duration-300"
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm z-[9999] flex items-center justify-center">
        <div class="text-center">
            <div class="relative w-24 h-24 mb-6 mx-auto">
                <!-- Animated loading spinner -->
                <div class="absolute inset-0 rounded-full border-4 border-cyan-500/20"></div>
                <div class="absolute inset-0 rounded-full border-t-4 border-cyan-500 animate-spin"></div>
                
                <!-- App logo or icon -->
                <div class="absolute inset-0 flex items-center justify-center animate-pulse">
                    <i class="fas fa-calendar-alt text-5xl text-cyan-400"></i>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-white mb-2">{{ __('calendar.loading') }}</h3>
            <p class="text-center text-white/80 mb-6">
                <span x-show="yearChanging">{{ __('calendar.year_changing') }}</span>
                <span x-show="!yearChanging">{{ __('calendar.preparing_calendar') }}</span>
            </p>
            
            <!-- Animated progress indicator -->
            <div class="w-64 h-2 mx-auto bg-gray-700/50 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>

    <!-- Main content - Hidden until fully loaded -->
    <div x-show="contentLoaded" x-transition:enter="transition-opacity duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <!-- Mobile Navigation Toggle Button - Only visible on small screens -->
        <div 
            x-data="{ mobileNavOpen: false }" 
            class="xl:hidden fixed top-4 right-4 z-[9999]">
            
            <button 
                @click="mobileNavOpen = true" 
                class="w-11 h-11 text-white bg-cyan-600 hover:bg-cyan-500 rounded-full shadow-lg flex items-center justify-center">
                <i class="fas fa-bars text-lg"></i>
            </button>
            
            <!-- Mobile Menu Background Overlay - Darkens the screen when menu is open -->
            <div 
                x-show="mobileNavOpen" 
                @click.away="mobileNavOpen = false" 
                x-cloak
                class="fixed inset-0 bg-black/50 z-40" 
                style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;">
            </div>
            
            <!-- Mobile Navigation Sidebar - Slides in from right -->
            <div 
                x-show="mobileNavOpen"
                @click.away="mobileNavOpen = false"
                class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                x-cloak>
                
                <!-- Mobile Navigation Sidebar Content - Glass morphism design -->
                <div class="absolute top-0 right-0 w-64 bg-white bg-opacity-10 border-white border-opacity-20 h-full shadow-lg rounded-l-xl transform transition-all duration-300"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-250"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full">
                    
                    <div class="p-4 relative h-full overflow-y-auto">
                        <!-- Header -->
                        <div class="flex justify-between items-center pb-4 mb-4 border-b border-white border-opacity-20">
                            <h3 class="font-bold text-white mb-3 mt-5"><i class="fas fa-map-marker-alt mr-2 text-amber-300"></i>
                                {{ __('calendar.navigation') }}
                            </h3>
                        </div>

                        <!-- Month navigation links for mobile view -->
                        <nav class="space-y-1">
                            @php
                                $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                                $currentMonthNumber = Carbon::now()->format('m');  // Format "05" pour mai
                            @endphp
                            <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-1 text-slate-100 hover:text-cyan-200 rounded-lg transition-colors cursor-pointer">
                                {{ __('calendar.scroll_to_top') }}
                            </a>
                            <a href="#{{ $currentMonthNumber }}" class="flex px-3 py-1 text-amber-300 hover:text-amber-200 rounded-lg transition-colors cursor-pointer">
                                {{ __('calendar.current_month') }}
                            </a>
                            @foreach ($months as $monthKey => $weeksInMonth)
                                @php
                                    if(substr($monthKey, 0, 4) != $year) {
                                        continue;
                                    }
                                    $monthInfo = $this->getMonthInfo($monthKey);
                                    $monthName = $monthInfo['name'];
                                    $monthNumber = $monthInfo['number'];
                                @endphp
                                <a href="#{{ $monthNumber }}" 
                                class="flex items-center justify-between px-3 py-1 rounded-xl text-slate-100 hover:text-cyan-200 transition-all duration-200 group">
                                    <span>{{ $monthName }}</span>
                                    <span class="text-sm text-slate-300 group-hover:text-cyan-200">
                                        {{ count($weeksInMonth) }} {{ __('calendar.weeks') }}
                                    </span>
                                </a>
                            @endforeach
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col xl:flex-row gap-4 lg:gap-8">
            <!-- Main content area - Contains the calendar display -->
            <div class="flex-1">
                <!-- Global stats panel - Shows yearly statistics -->
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-8">
                    <!-- Stats section -->
                    <div class="flex flex-col lg:flex-row gap-4 sm:gap-8">
                        <!-- Stats wrapper -->
                        <div class="hidden sm:flex justify-between lg:justify-start gap-4 lg:gap-16 cursor-default">
                            @foreach(['distance', 'duration', 'elevation'] as $stat)
                            <div class="flex items-center gap-4">
                                <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-400 text-4xl"></i>
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-slate-300 mb-1">{{ __('calendar.stats.'.$stat) }}</p>
                                    <div class="flex items-baseline">
                                        <span class="text-2xl font-bold text-white">
                                            @if($stat === 'distance')
                                                <span class="whitespace-nowrap">{{ number_format($yearStats['actual'][$stat], 0, ',', '') }} <span class="text-sm text-gray-400">km</span></span>                                            
                                            @elseif($stat === 'duration')
                                                {{ formatTime((int)($yearStats['actual'][$stat])) }}
                                            @else
                                                <span class="whitespace-nowrap">{{ number_format($yearStats['actual'][$stat], 0, ',', '') }} <span class="text-sm text-gray-400">m</span></span>                                            
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Controls wrapper -->
                        <div class="flex flex-row justify-between lg:justify-end lg:ml-10 gap-4 items-center w-full">
                            <div class="relative flex-grow lg:max-w-xl" x-data="{ isLoading: false }" 
                                x-on:livewire:navigating="isLoading = true"
                                x-on:livewire:navigated="isLoading = false">
                                <div class="flex items-center justify-between gap-2 py-2 px-4 bg-white bg-opacity-10 border-white border-opacity-20 rounded-xl shadow-lg w-full">
                                    <button 
                                        wire:click="previousYear" 
                                        @click="$dispatch('year-navigation-start')"
                                        type="button"
                                        :disabled="isLoading"
                                        :class="{ 'opacity-30 cursor-not-allowed': isLoading }"
                                        class="flex items-center justify-center w-10 h-10 text-white bg-cyan-600 hover:bg-cyan-500 bg-opacity-70 rounded-lg hover:bg-opacity-100 transition-all transform"
                                        data-tippy-content="Previous year">
                                        <span x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                        <i x-show="!isLoading" class="fas fa-chevron-left"></i>
                                    </button>
                                    
                                    <div class="flex items-center justify-center gap-3 py-2 px-4 relative">
                                        <span x-show="isLoading" class="absolute top-0 right-0 -mr-1 -mt-1 w-3 h-3">
                                            <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                        </span>
                                        <span class="font-bold text-2xl text-white">{{ $year }}</span>
                                    </div>
                                    
                                    <button 
                                        wire:click="nextYear"
                                        @click="$dispatch('year-navigation-start')"
                                        type="button"
                                        :disabled="isLoading"
                                        :class="{ 'opacity-30 cursor-not-allowed': isLoading }"
                                        class="flex items-center justify-center w-10 h-10 text-white bg-cyan-600 hover:bg-cyan-500 bg-opacity-70 rounded-lg hover:bg-opacity-100 transition-all transform"
                                        data-tippy-content="Next year">
                                        <span x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                                            <i class="fas fa-spinner fa-spin"></i>
                                        </span>
                                        <i x-show="!isLoading" class="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex gap-2 flex-shrink-0" 
                                x-data="{ 
                                    syncInProgress: @entangle('syncInProgress').live,
                                    loading: @entangle('loading').live,
                                    pollInterval: null,
                                    startSyncPolling: function() {
                                        // Démarrer un polling léger uniquement pendant la sync
                                        if (this.syncInProgress && !this.pollInterval) {
                                            this.pollInterval = setInterval(() => {
                                                $wire.call('checkSyncStatus');
                                            }, 2000); // Vérifier toutes les 2 secondes
                                        }
                                    },
                                    stopSyncPolling: function() {
                                        if (this.pollInterval) {
                                            clearInterval(this.pollInterval);
                                            this.pollInterval = null;
                                        }
                                    }
                                }"
                                x-init="() => {
                                    // Vérifier si un rafraîchissement du calendrier est nécessaire après sync
                                    if (localStorage.getItem('calendar_needs_refresh') === 'true') {
                                        setTimeout(function() {
                                            $wire.dispatch('sync-completed-refresh');
                                            localStorage.removeItem('calendar_needs_refresh');
                                        }, 500);
                                    }
                                    
                                    // Vérifier le statut de sync immédiatement au chargement de la page
                                    // Ceci est crucial pour détecter si une sync a été démarrée par le StravaController
                                    $wire.call('checkSyncStatus').then(() => {
                                        // Après avoir vérifié le statut, démarrer le polling si nécessaire
                                        if (syncInProgress) {
                                            startSyncPolling();
                                        }
                                    });
                                    
                                    // Écouter les événements de changement de statut depuis le polling global
                                    $wire.on('sync-completed-refresh', function() {
                                        // S'assurer que syncInProgress est bien remis à false
                                        syncInProgress = false;
                                        // Le rafraîchissement sera géré par le composant Livewire
                                        stopSyncPolling();
                                    });
                                    
                                    // Surveiller les changements de syncInProgress
                                    $watch('syncInProgress', function(value) {
                                        if (value) {
                                            startSyncPolling();
                                        } else {
                                            stopSyncPolling();
                                        }
                                    });
                                    
                                    // Nettoyer le polling en quittant la page
                                    window.addEventListener('beforeunload', function() {
                                        stopSyncPolling();
                                    });
                                }">
                                <button 
                                    wire:click.prevent="startSync"
                                    x-bind:disabled="syncInProgress || loading"
                                    x-bind:class="syncInProgress || loading
                                        ? 'bg-amber-600 cursor-not-allowed' 
                                        : 'bg-amber-600 hover:bg-amber-500 cursor-pointer'"
                                    class="w-14 h-16 relative group py-3 px-4 text-white rounded-xl transition-colors"
                                    x-bind:data-tippy-content="syncInProgress 
                                        ? '{{ __('calendar.messages.sync_already_in_progress') }}' 
                                        : 'Synchronize with Strava'">
                                    <!-- Icône Strava normale quand pas en sync -->
                                    <span x-show="!syncInProgress && !loading" 
                                          class="flex items-center justify-center">
                                        <i class="fab fa-strava text-2xl"></i>
                                    </span>
                                    
                                    <!-- Spinner circulaire unique pour tous les états de chargement -->
                                    <span x-show="syncInProgress || loading" 
                                          class="flex items-center justify-center">
                                        <div class="w-6 h-6 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                    </span>
                                </button>  

                                <!-- Year Options Dropdown Menu Button -->
                                <x-dropdown trigger-icon="ellipsis-v" trigger-class="py-3 ps-5 px-2 text-2xl" align="right">
                                    <div class="py-1">
                                        <div class="border-b border-white border-opacity-10 pb-2 mb-2">
                                            <x-dropdown-item icon="chevron-down" iconColor="cyan" @click="toggleAllWeeks(true); open = false">
                                                {{ __('calendar.collapse_all_weeks') }}
                                            </x-dropdown-item>
                                            <x-dropdown-item icon="chevron-up" iconColor="cyan" @click="toggleAllWeeks(false); open = false">
                                                {{ __('calendar.expand_all_weeks') }}
                                            </x-dropdown-item>
                                        </div>
                                        <x-dropdown-item wire:click.prevent="deleteAll" icon="trash-alt" iconColor="red">
                                            {{ __('calendar.delete_all_workouts') }}
                                        </x-dropdown-item>
                                    </div>
                                </x-dropdown>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Months -->
                @foreach ($months as $monthKey => $weeksInMonth)
                    @if(isset($monthStats[$monthKey]))
                    @php
                        $monthInfo = $this->getMonthInfo($monthKey);
                        $monthName = $monthInfo['name'];
                        $monthNumber = $monthInfo['number'];
                    @endphp
                    <section id="{{ $monthNumber }}" class="mb-4 sm:mb-5" data-month-key="{{ $monthKey }}" data-month-number="{{ $monthNumber }}">
                        <!-- Month header -->
                        <h2>
                            <div class="flex flex-row items-center gap-3 py-2">
                                <span class="text-2xl font-bold text-white ms-2">{{ $monthName }}</span>
                                <!-- Mobile Month Controls - Now on same line as month name, visible only on mobile -->
                                <div class="flex sm:hidden items-center gap-2 ml-auto">   
                                    <!-- Month menu contextuel for mobile -->
                                    <x-dropdown trigger-icon="ellipsis-v" trigger-class="py-3 px-2 text-gray-400 hover:text-white rounded-xl transition-colors focus:outline-none" align="right">
                                        <div class="py-1">
                                            <x-dropdown-item wire:click.prevent="deleteMonth('{{ $monthKey }}')" icon="trash-alt" iconColor="red">
                                                {{ __('calendar.delete_monthly_workouts') }}
                                            </x-dropdown-item>
                                        </div>
                                    </x-dropdown>
                                </div>
                                
                                <!-- Month stats - Only visible on desktop -->
                                <div class="ml-auto hidden sm:flex cursor-default">
                                    <div class="flex items-center gap-3 px-4 bg-white bg-opacity-10 rounded-lg shadow-lg">                                    
                                        @foreach(['distance', 'duration', 'elevation'] as $stat)
                                            <div class="flex flex-row items-center gap-2 py-2">
                                                <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-400"></i>
                                                <div class="flex items-end">
                                                    <span class="font-semibold text-white">
                                                        @if($stat === 'distance')
                                                            {{ number_format($monthStats[$monthKey]['actual'][$stat] ?? 0, 1) }}
                                                        @elseif($stat === 'duration')
                                                            {{ formatTime((int)($monthStats[$monthKey]['actual'][$stat] ?? 0)) }}
                                                        @else
                                                            {{ $monthStats[$monthKey]['actual'][$stat] ?? 0 }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach      
                                        <x-dropdown trigger-icon="ellipsis-v" trigger-class="py-3 px-2 text-gray-400 hover:text-white rounded-lg transition-colors focus:outline-none" align="right">
                                            <div class="py-1">
                                                <x-dropdown-item wire:click.prevent="deleteMonth('{{ $monthKey }}')" icon="trash-alt" iconColor="red">
                                                    {{ __('calendar.delete_monthly_workouts') }}
                                                </x-dropdown-item>
                                            </div>
                                        </x-dropdown>
                                    </div>
                                </div>
                            </div>
                        </h2>

                        <!-- Weeks -->
                        @foreach ($weeksInMonth as $week)
                            @php
                                $baseColor = $week->type ? $week->type->color : 'bg-slate-500';
                                $colorPalette = $this->getWeekColorPalette($baseColor);
                            @endphp
                            <div wire:key="week-{{ $week->id }}" x-data="{ collapsed: false, weekId: '{{ $week->id }}', monthKey: '{{ $monthKey }}', year: '{{ $year }}', storageKey() { return `weekState_${this.year}_${this.weekId}`; }, saveState() { localStorage.setItem(this.storageKey(), this.collapsed ? '1' : '0'); } }" x-init="$nextTick(() => { collapsed = localStorage.getItem(storageKey()) === '1'; })" @toggle-week-collapse.window="if ($event.detail.weekId === weekId) { collapsed = !collapsed; saveState(); }" class="relative rounded-xl shadow-lg ps-2 mb-2 overflow-visible bg-white bg-opacity-10 {{ $week->is_current_week ? 'ring-2 ring-amber-300/60 ring-offset-1 ring-offset-slate-900/40' : '' }}">
                                <!-- Background overlay plus visible -->
                                <div class="absolute inset-0 rounded-xl opacity-20 bg-gradient-to-br from-{{ $colorPalette['lightShade'] }} via-{{ $colorPalette['midShade'] }} to-{{ $colorPalette['darkShade'] }}"></div>
                                
                                <!-- Bande colorée à gauche pour une identification plus marquée -->
                                <div class="absolute left-0 top-0 bottom-0 w-2 bg-{{ $colorPalette['midShade'] }} rounded-l-xl"></div>
                                
                                <!-- Contenu de la semaine -->
                                <div class="relative z-10 week-header pt-2 pb-1 px-2">
                                    <div class="flex flex-col gap-4">
                                        <!-- Week info and controls -->
                                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">                                                <div class="flex justify-between sm:flex-col gap-2">
                                                <div class="flex flex-wrap items-center gap-2 cursor-default">
                                                    <span class="hidden sm:block px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray">
                                                        {{ __('calendar.week') }} {{ $week->week_number }} <span class="text-gray-600">({{ $week->start }} - {{ $week->end }})</span>
                                                    </span>
                                                    <span class="sm:hidden text-sm text-white">
                                                        {{ __('calendar.week') }} {{ $week->week_number }} <span class="text-gray-300">({{ $week->start }} - {{ $week->end }})</span>
                                                    </span>
                                                </div>
                                            
                                                <div class="flex items-center gap-2">
                                                    <!-- Week Type Button with Dropdown -->
                                                    <x-dropdown trigger="custom" align="left" width="w-44" teleport="true" :autoClose="false">
                                                        <x-slot name="button">
                                                            <button 
                                                                class="text-white bg-cyan-600 hover:bg-cyan-500 py-1.5 px-3 text-sm rounded-md flex items-center gap-2 w-40">
                                                                <i class="fas fa-tag"></i>
                                                                @if($week->type)
                                                                    <span class="flex items-center gap-2">
                                                                        <span class="w-3 h-3 rounded-full {{ $week->type->color }}"></span>
                                                                        {{ $week->type->getLocalizedName() }}
                                                                    </span>
                                                                @else
                                                                    <span>{{ __('calendar.set_week_type') }}</span>
                                                                @endif
                                                            </button>
                                                        </x-slot>
                                                        
                                                        <div class="pb-1 max-h-80 overflow-y-auto">
                                                            <!-- Option to set no week type -->
                                                            <x-dropdown-item wire:click="setWeekType({{ $week->id }}, null)">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="w-4 h-4 rounded-full bg-gray-500 bg-opacity-30"></span>
                                                                    <span>{{ __('calendar.none') }}</span>
                                                                </div>
                                                            </x-dropdown-item>
                                                            
                                                            <!-- Divider -->
                                                            <div class="my-1 border-t border-white border-opacity-10"></div>
                                                            @foreach($weekTypes as $weekType)
                                                                <x-dropdown-item wire:click="setWeekType({{ $week->id }}, {{ $weekType->id }})">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="w-4 h-4 rounded-full {{ $weekType->color }}"></span>
                                                                        <span>{{ $weekType->getLocalizedName() }}</span>
                                                                    </div>
                                                                </x-dropdown-item>
                                                            @endforeach
                                                        </div>
                                                    </x-dropdown>
                                                    
                                                    <!-- Collapse/Expand Button with chevron -->
                                                    <button 
                                                        @click="collapsed = !collapsed; saveState()" 
                                                        class="flex py-1.5 ps-2 items-center justify-center text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none collapse-toggle"
                                                        data-tippy-content="{{ __('calendar.toggle_week') }}">
                                                        <i class="fas" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                                                    </button>

                                                    <x-dropdown trigger-icon="ellipsis-v" trigger-class="py-1.5 px-2 text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none" align="right">
                                                        <div class="py-1">
                                                            <x-dropdown-item wire:click.prevent="deleteWeek('{{ $week->id }}')" icon="trash-alt" iconColor="red">
                                                                {{ __('calendar.delete_weekly_workouts') }}
                                                            </x-dropdown-item>
                                                        </div>
                                                    </x-dropdown>
                                                </div>
                                            </div>

                                            <!-- Week stats -->
                                            <div class="flex justify-between mb-1 gap-4 cursor-default">
                                                @foreach(['distance', 'duration', 'elevation'] as $stat)
                                                <!-- Structure for all stat displays with consistent height regardless of content -->
                                                    <div class="flex flex-col w-32">
                                                        <!-- Stat increase display -->
                                                        <div class="flex justify-center h-5">
                                                            @if($stat === 'distance' || $stat === 'duration')
                                                                @php
                                                                    $progressData = $this->calculateDevelopmentWeekProgress($week);
                                                                    $statProgressData = $progressData[$stat] ?? null;
                                                                @endphp
                                                                
                                                                @if($statProgressData && isset($statProgressData['value']))
                                                                    @php
                                                                        $increase = $statProgressData['value'];
                                                                    @endphp
                                                                    @if($increase > 10)
                                                                        <span class="text-red-400 text-xs font-semibold">
                                                                            <i class="fas fa-exclamation-triangle text-2xs mr-1"></i>{{ $increase }}%
                                                                        </span>
                                                                    @elseif($increase != 0)
                                                                        <span class="text-emerald-400 text-xs font-semibold">
                                                                            <i class="fas fa-circle-check text-2xs mr-1"></i>{{ $increase }}%
                                                                        </span>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </div>

                                                        <!-- Stat value display -->
                                                        <div class="flex items-center justify-center gap-2 px-1 md:px-2">
                                                            <p class="text-xs flex items-center">
                                                                <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-400 text-lg"></i>
                                                            </p>
                                                            <div class="flex items-end">
                                                                <span class="text-lg font-bold text-slate-300">
                                                                    @if($stat === 'distance')
                                                                        {{ number_format($week->actual_stats[$stat] ?? 0, 1) }}
                                                                    @elseif($stat === 'duration')
                                                                        {{ formatTime((int)($week->actual_stats[$stat] ?? 0)) }}
                                                                    @else
                                                                        {{ $week->actual_stats[$stat] ?? 0 }}
                                                                    @endif
                                                                </span>
                                                                @if(($week->planned_stats[$stat] ?? 0) > 0)
                                                                    <span class="text-sm text-gray-400 ml-0.5 whitespace-nowrap flex items-end mb-0.5">
                                                                        /&nbsp;<span>
                                                                            @if($stat === 'duration')
                                                                                {{ formatTime($week->planned_stats[$stat] ?? 0) }}
                                                                            @else
                                                                                {{ $stat === 'distance' ? number_format($week->planned_stats[$stat] ?? 0, 1) : ($week->planned_stats[$stat] ?? 0) }}
                                                                            @endif
                                                                        </span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Stat progress bar -->
                                                        @if(($week->planned_stats[$stat] ?? 0) > 0)                                
                                                            @php 
                                                                $percentage = $this->calculateCompletionPercentage(
                                                                    $week->actual_stats[$stat] ?? null, 
                                                                    $week->planned_stats[$stat] ?? null
                                                                );
                                                            @endphp
                                                            <div class="w-full mt-1 h-2 bg-gray-800 bg-opacity-50 rounded-full">
                                                                <div class="h-2 bg-{{ $statColors[$stat] }}-400 rounded-full" 
                                                                    style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-2 pb-1" x-show="!collapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                        <div class="grid grid-cols-2 lg:grid-cols-7 gap-3">
                                            @foreach ($week->days as $day)
                                                @php
                                                    $dayDate = $day->date;
                                                    $isLastDayInWeek = $loop->last;
                                                    $isOddDayInList = $loop->iteration % 2 !== 0;
                                                    $shouldTakeFullWidth = $isLastDayInWeek && $isOddDayInList;
                                                @endphp
                                                <div wire:key="day-{{ $dayDate->format('Y-m-d') }}"
                                                    ondragover="onDragOver(event)"
                                                    ondrop="onDrop(event, '{{ $dayDate->format('Y-m-d') }}')"
                                                    ondragleave="onDragLeave(event)"
                                                    wire:click.stop="$dispatch('openModal', { component: 'modal.workout-modal', attributes: { date: '{{ $dayDate->format('Y-m-d') }}' }})"
                                                    class="relative block p-2 rounded-xl shadow-lg {{ $dayDate->isToday() ? 'ring-2 ring-amber-300/60 ring-offset-1 ring-offset-slate-900/40' : '' }} min-h-24 cursor-pointer bg-gradient-to-b from-slate-800/40 to-slate-900/40 {{ $shouldTakeFullWidth ? 'col-span-2 lg:col-span-1' : '' }}">
                                                    <!-- Day date display in calendar cell -->
                                                    <div class="absolute top-2 left-2">
                                                        <div>
                                                            <span class="text-sm text-cyan-200">{{ $dayDate->isoFormat('ddd') }}</span>
                                                            <span class="text-sm font-bold text-white">{{ $dayDate->day }}</span>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Completed activities badges - Shows Strava activities for this day -->
                                                    @php 
                                                        $dayActivities = $activities->filter(fn($activity) => $activity->day_id === $day->id);
                                                    @endphp
                                                    @if($dayActivities->isNotEmpty())
                                                        <div class="absolute top-2 right-2 flex flex-wrap justify-end gap-1.5">
                                                            @foreach($dayActivities as $activity)
                                                            <div class="relative">
                                                                <a wire:click.stop="$dispatch('openModal', { component: 'modal.activity-modal', attributes: { id: '{{ $activity->id }}' }})" class="relative cursor-pointer block" data-tippy-content="{{ $activity->name }}">
                                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 text-white">
                                                                        <i class="fas fa-running text-sm"></i>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    <!-- Workout badges -->
                                                    @php 
                                                        $dayWorkouts = $workouts->filter(fn($workout) => $workout->day_id === $day->id);
                                                    @endphp
                                                    @if($dayWorkouts->isNotEmpty())
                                                        <div class="absolute bottom-2 left-2 flex flex-wrap gap-1.5 max-w-[80%]">
                                                            @foreach($dayWorkouts as $workout)
                                                                <div class="relative">
                                                                    <a wire:click.stop="$dispatch('openModal', { component: 'modal.workout-modal', attributes: { id: '{{ $workout->id }}' }})" 
                                                                       class="relative cursor-pointer block" 
                                                                       draggable="true" 
                                                                       ondragstart="onDragStart(event, {{ $workout->id }})" 
                                                                       data-tippy-content="{{ ($workout->type ? '<div class=\'font-medium mb-0.5\'>' . $workout->type->getLocalizedName() . '</div>' : '<div class=\'font-medium mb-0.5\'>Workout</div>') . '<div class=\'flex flex-wrap gap-x-2 text-gray-300 text-xs\'>' . ($workout->duration > 0 ? '<span class=\'whitespace-nowrap\'><i class=\'fas fa-stopwatch mr-1\'></i>' . formatTime($workout->duration * 60) . '</span>' : '') . ($workout->distance > 0 ? '<span class=\'whitespace-nowrap\'><i class=\'fas fa-route mr-1\'></i>' . formatDistance($workout->distance) . '</span>' : '') . ($workout->elevation > 0 ? '<span class=\'whitespace-nowrap\'><i class=\'fas fa-mountain mr-1\'></i>' . $workout->elevation . 'm</span>' : '') . '</div>' . ($workout->notes != '' ? '<div class=\'mt-1 text-gray-200 text-xs line-clamp-2\'>' . $workout->notes . '</div>' : '') }}"
                                                                       data-date="{{ $day->date }}"
                                                                       data-element-id="workout-{{ $workout->id }}"
                                                                       data-week="{{ $day->week->week_number }}">
                                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $workout->type ? $workout->type->color : 'bg-gray-500' }} text-white">
                                                                            <i class="{{ $workout->type->icon }} text-sm"></i>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </section>
                @endif
                @endforeach

            </div>

            <!-- Side navigation panel - Fixed position on desktop -->
            <div class="xl:w-52">
                <div class="xl:fixed">
                    <!-- Desktop navigation sidebar - Only visible on large screens -->
                    <div class="hidden lg:block">
                        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg p-4">
                            <h3 class="font-bold text-white mt-1 pb-4 mb-4 border-b border-white border-opacity-20"><i class="fas fa-map-marker-alt mr-2 text-amber-300"></i>
                                {{ __('calendar.navigation') }}
                            </h3>
                            <nav class="">
                                @php
                                    $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                                    $currentMonthNumber = Carbon::now()->format('m');
                                @endphp
                                <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-1 text-slate-100 hover:text-cyan-200 rounded-lg transition-colors cursor-pointer">
                                    {{ __('calendar.scroll_to_top') }}
                                </a>
                                <a href="#{{ $currentMonthNumber }}" class="flex px-3 py-1 text-amber-300 hover:text-amber-200 rounded-lg transition-colors cursor-pointer">
                                    {{ __('calendar.current_month') }}
                                </a>
                                @foreach ($months as $monthKey => $weeksInMonth)
                                    @php
                                        if(substr($monthKey, 0, 4) != $year) {
                                            continue;
                                        }
                                        $monthInfo = $this->getMonthInfo($monthKey);
                                        $monthName = $monthInfo['name'];
                                        $monthNumber = $monthInfo['number'];
                                    @endphp
                                    <a href="#{{ $monthNumber }}" 
                                    class="flex items-center justify-between px-3 py-1 gap-2 rounded-xl text-slate-100 hover:text-cyan-200 transition-all duration-200 group">
                                        <span>{{ $monthName }}</span>
                                        <span class="text-sm text-slate-300">
                                            {{ count($weeksInMonth) }} {{ __('calendar.weeks') }}
                                        </span>
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>        
        /* Hide scrollbars for better UI aesthetics while maintaining scroll functionality */
        .mx-auto.overflow-y-scroll,
        .overflow-y-auto,
        .p-4.relative.h-full.overflow-y-auto {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .mx-auto.overflow-y-scroll::-webkit-scrollbar,
        .overflow-y-auto::-webkit-scrollbar,
        .p-4.relative.h-full.overflow-y-auto::-webkit-scrollbar {
            display: none;
        }

        /* Hide elements until Alpine.js is initialized */
        [x-cloak] {
            display: none !important;
        }
    </style>
    
    <!-- Script pour le défilement via localStorage -->
    <script>
        // Auto-execute immediately to set up scroll before content is displayed
        (function() {
            // Vérifier si on a un mois enregistré dans localStorage
            var targetMonthId = localStorage.getItem('scrollToMonth');
            if (targetMonthId) {
                // Supprimer l'item pour éviter de scroller automatiquement lors des visites futures
                localStorage.removeItem('scrollToMonth');
                
                // Configurer une fonction qui tentera de faire défiler dès que possible
                function attemptScroll() {
                    // Cette fonction sera appelée plusieurs fois jusqu'à ce qu'elle trouve l'élément
                    var targetElement = document.getElementById(targetMonthId);
                    if (targetElement) {
                        // On a trouvé l'élément, on fait défiler immédiatement
                        targetElement.scrollIntoView({behavior: 'auto', block: 'start'});
                        return true;
                    }
                    return false;
                }
                
                // Mettre en place un MutationObserver pour détecter quand l'élément apparaît dans le DOM
                var observer = new MutationObserver(function(mutations) {
                    if (attemptScroll()) {
                        // Si on a réussi à défiler, on arrête d'observer
                        observer.disconnect();
                    }
                });
                
                // Commencer à observer le document pour les changements dans le DOM
                observer.observe(document.documentElement, { childList: true, subtree: true });
                
                // Programmer plusieurs tentatives de défilement pour s'assurer que cela fonctionne
                var attempts = 0;
                var maxAttempts = 10;
                var attemptInterval = setInterval(function() {
                    if (attemptScroll() || ++attempts >= maxAttempts) {
                        clearInterval(attemptInterval);
                    }
                }, 200);
                
                // Ajout d'un événement sur le chargement de la page
                window.addEventListener('load', function() {
                    // Tentative finale de défilement après le chargement complet
                    setTimeout(attemptScroll, 500);
                });
            }
        })();
    </script>
</div>
<script>
// --- Drag & Drop & Module d'interface optimisé ---
document.addEventListener('DOMContentLoaded', function() {
    // S'assurer que Livewire est disponible avant de créer le CalendarUI
    if (typeof Livewire === 'undefined') {
        // Attendre le chargement de Livewire
        document.addEventListener('livewire:init', initCalendarUI);
    } else {
        initCalendarUI();
    }

    function initCalendarUI() {
        window.CalendarUI = (function() {
            // Drag & Drop
            var dragListenersAdded = false;
            function onDragStart(event, workoutId) {
                const isCopy = event.ctrlKey || event.metaKey;
                
                // Set data in the format expected by the app-wide handlers
                event.dataTransfer.setData('text/plain', JSON.stringify({
                    workoutId,
                    isCopy
                }));
                
                // Set the drag effect (copy or move)
                event.dataTransfer.effectAllowed = isCopy ? 'copy' : 'move';
                
                // Visual feedback
                if(isCopy) {
                    event.currentTarget.classList.add('dragging-copy');
                } else {
                    event.currentTarget.classList.add('opacity-50');
                }
                
                if (!dragListenersAdded) {
                    document.querySelectorAll('[ondragover], [ondrop], [ondragleave]').forEach(function(el) {
                        el.addEventListener('dragover', onDragOver, { passive: false });
                        el.addEventListener('drop', onDropWrapper, { passive: false });
                        el.addEventListener('dragleave', onDragLeave, { passive: false });
                    });
                    dragListenersAdded = true;
                }
            }
            
            function onDragOver(event) {
                // Prevent default to allow drop
                event.preventDefault();
                
                // Set the drop effect based on mode (copy or move)
                if (event.dataTransfer.getData('text/plain')) {
                    try {
                        const data = JSON.parse(event.dataTransfer.getData('text/plain'));
                        event.dataTransfer.dropEffect = data.isCopy ? 'copy' : 'move';
                    } catch (error) {
                        event.dataTransfer.dropEffect = 'move';
                    }
                }
                
                // Add visual feedback class
                event.currentTarget.classList.add('bg-cyan-600', 'bg-opacity-20', 'drag-over');
            }
            
            function onDragLeave(event) {
                event.currentTarget.classList.remove('bg-cyan-600', 'bg-opacity-20', 'drag-over');
            }
            
            function onDropWrapper(event) {
                // Prevent default actions
                event.preventDefault();
                event.stopPropagation();
                
                // Get the target date from the element's ondrop attribute
                var date = event.currentTarget.getAttribute('ondrop').match(/'(.*?)'/)[1];
                
                try {
                    // Extract workout data
                    const data = JSON.parse(event.dataTransfer.getData('text/plain'));
                    const workoutId = data.workoutId;
                    const isCopy = data.isCopy;
                    
                    // Remove visual feedback
                    event.currentTarget.classList.remove('bg-cyan-600', 'bg-opacity-20', 'drag-over');
                    
                    // Remove opacity from original element
                    document.querySelectorAll('.opacity-50, .dragging-copy').forEach(el => {
                        el.classList.remove('opacity-50', 'dragging-copy');
                    });
                    
                    // Call Livewire method based on whether this is a copy or move
                    if (typeof Livewire !== 'undefined') {
                        if(isCopy) {
                            Livewire.dispatch('workout-copied', {
                                workoutId: parseInt(workoutId),
                                newDate: date
                            });
                        } else {
                            Livewire.dispatch('workout-moved', {
                                workoutId: parseInt(workoutId),
                                newDate: date
                            });
                        }
                    }
                } catch (error) {
                    // Silent error handling for drag and drop
                }
            }
            
            // Expose drag functions globally for blade inline events
            window.onDragStart = onDragStart;
            window.onDragOver = onDragOver;
            window.onDragLeave = onDragLeave;
            window.onDrop = function(event, date) { return onDropWrapper(event); };
            
            // Collapse/Expand all weeks
            window.toggleAllWeeks = function(shouldCollapse) {
                document.querySelectorAll('[wire\\:key^="week-"]').forEach(function(weekEl) {
                    var weekComponent = Alpine.$data(weekEl);
                    if (weekComponent && weekComponent.collapsed !== shouldCollapse) {
                        weekComponent.collapsed = shouldCollapse;
                        weekComponent.saveState();
                    }
                });
            };
            
            return { 
                onDragStart: onDragStart, 
                onDragOver: onDragOver, 
                onDragLeave: onDragLeave, 
                onDropWrapper: onDropWrapper
            };
        })();
    }

    // Handle URL anchor scrolling during page load
    (function() {
        // Get the anchor from URL (remove the # symbol)
        var urlAnchor = window.location.hash.substring(1);
        
        if (urlAnchor) {
            // Function to attempt scrolling to the element
            function attemptScrollToAnchor() {
                var targetElement = document.getElementById(urlAnchor);
                if (targetElement) {
                    // Element found, scroll to it
                    targetElement.scrollIntoView({behavior: 'auto', block: 'start'});
                    return true;
                }
                return false;
            }
            
            // Start observing DOM changes to detect when the anchor element is added
            var observer = new MutationObserver(function(mutations) {
                if (attemptScrollToAnchor()) {
                    // If successful, stop observing
                    observer.disconnect();
                }
            });
            
            // Start observing the document
            observer.observe(document.documentElement, { childList: true, subtree: true });
            
            // Make multiple attempts to scroll
            var attempts = 0;
            var maxAttempts = 10;
            var attemptInterval = setInterval(function() {
                if (attemptScrollToAnchor() || ++attempts >= maxAttempts) {
                    clearInterval(attemptInterval);
                }
            }, 200);
            
            // Final attempt after page load
            window.addEventListener('load', function() {
                setTimeout(attemptScrollToAnchor, 500);
            });
        }
    })();
});
</script>
