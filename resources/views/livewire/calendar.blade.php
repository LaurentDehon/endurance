<?php
    use App\http\Helpers\Helpers; 
    use Carbon\Carbon;
?>    

<div class="mx-auto p-2 sm:p-4 overflow-y-scroll relative"
    x-data="{ contentLoaded: false }" 
    x-init="setTimeout(() => { contentLoaded = true }, 500)">
    <!-- Fixed gradient background covering the entire page -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 -z-10"></div>

    <!-- Strava Synchronization Loading Overlay -->
    <!-- This overlay appears when syncing with Strava API -->
    <div
        wire:loading
        wire:target="startSync"
        class="fixed inset-0 bg-black/70 backdrop-blur-md z-[9999] flex items-center justify-center">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center p-8 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-700/20 border border-amber-500/30 backdrop-blur-lg shadow-2xl max-w-md w-full">
            <div class="relative w-24 h-24 mb-6">
                <!-- Animated loading spinner with Strava branding -->
                <div class="absolute inset-0 rounded-full border-4 border-amber-500/20"></div>
                <div class="absolute inset-0 rounded-full border-t-4 border-amber-500 animate-spin"></div>
                
                <!-- Strava logo with pulse animation -->
                <div class="absolute inset-0 flex items-center justify-center animate-pulse">
                    <i class="fab fa-strava text-5xl text-amber-500"></i>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-white mb-2">Strava Synchronization</h3>
            <p class="text-center text-white/80 mb-6">Retrieving your workout data from Strava...</p>
            
            <!-- Animated progress indicator for sync process -->
            <div class="w-full h-2 bg-gray-700/50 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>

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
            
            <h3 class="text-2xl font-bold text-white mb-2">Loading</h3>
            <p class="text-center text-white/80 mb-6">Preparing your calendar...</p>
            
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
                                Navigation
                            </h3>
                        </div>

                        <!-- Month navigation links for mobile view -->
                        <nav class="space-y-1">
                            @php
                                $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                            @endphp
                            <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-1 text-slate-100 hover:text-cyan-200 rounded-lg transition-colors cursor-pointer">
                                Scroll to top
                            </a>
                            
                            @foreach ($months as $monthKey => $weeksInMonth)
                                @php
                                    if(substr($monthKey, 0, 4) != $year) {
                                        continue;
                                    }
                                    $monthInfo = $this->getMonthInfo($monthKey);
                                    $monthName = $monthInfo['name'];
                                @endphp
                                <a href="#{{ Str::slug($monthName) }}" 
                                class="flex items-center justify-between px-3 py-1 rounded-xl text-slate-100 hover:text-cyan-200 transition-all duration-200 group">
                                    <span>{{ $monthName }}</span>
                                    <span class="text-sm text-slate-300 group-hover:text-cyan-200">
                                        {{ count($weeksInMonth) }} weeks
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
                <div class="bg-white bg-opacity-10 border-white border-opacity-20 border backdrop-blur-lg rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-8">
                    <!-- Stats section -->
                    <div class="flex flex-col lg:flex-row gap-4 sm:gap-8">
                        <!-- Stats wrapper -->
                        <div class="hidden sm:flex justify-between lg:justify-start gap-4 lg:gap-16 cursor-default">
                            @foreach(['distance', 'duration', 'elevation'] as $stat)
                            <div class="flex items-center gap-4">
                                <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-400 text-4xl"></i>
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-slate-300 mb-1">{{ ucfirst($stat) }}</p>
                                    <div class="flex items-baseline">
                                        <span class="text-2xl font-bold text-white">
                                            @if($stat === 'distance')
                                                {{ number_format($yearStats['actual'][$stat], 0, ',', '') }}
                                            @elseif($stat === 'duration')
                                                {{ formatTime((int)($yearStats['actual'][$stat])) }}
                                            @else
                                                {{ number_format($yearStats['actual'][$stat], 0, ',', '') }}
                                            @endif
                                        </span>
                                        
                                        @if($yearStats['planned'][$stat] > 0)
                                            <span class="text-sm text-gray-400 ml-1.5 whitespace-nowrap">
                                                <span class="me-1">/</span>{{ $stat === 'duration' ? formatTime($yearStats['planned'][$stat]) : ($stat === 'distance' ? number_format($yearStats['planned'][$stat], 0, ',', '') : number_format($yearStats['planned'][$stat], 0, ',', '')) }}
                                            </span>
                                        @endif
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
                            
                            <div class="flex gap-2 flex-shrink-0">
                                <button 
                                    wire:click.prevent="startSync" 
                                    class="relative group py-3 px-4 bg-amber-600 text-white hover:bg-amber-500 rounded-xl transition-colors"
                                    data-tippy-content="Synchronize with Strava">
                                    <i class="fab fa-strava text-2xl" wire:loading.class="animate-spin" wire:target="startSync"></i>
                                </button>  

                                <!-- Year Options Dropdown Menu Button -->
                                <div class="relative block" x-data="{ open: false }">
                                    <button @click="open = !open" class="py-3 ps-5 px-2 text-gray-400 hover:text-white rounded-xl transition-colors focus:outline-none">
                                        <i class="fas fa-ellipsis-v text-2xl"></i>
                                    </button>
                                    
                                    <!-- Dropdown menu using teleport -->
                                    <template x-teleport="body">
                                        <div x-show="open" 
                                             x-effect="
                                                if (open) {
                                                    $nextTick(() => {
                                                        const button = $root.previousElementSibling;
                                                        const rect = button.getBoundingClientRect();
                                                        $el.style.top = `${rect.bottom + window.scrollY + 5}px`;
                                                        $el.style.left = `${rect.left - 210 + rect.width}px`;
                                                    });
                                                }
                                             "
                                             @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200" 
                                             x-transition:enter-start="opacity-0 scale-95" 
                                             x-transition:enter-end="opacity-100 scale-100" 
                                             x-transition:leave="transition ease-in duration-175" 
                                             x-transition:leave-start="opacity-100 scale-100" 
                                             x-transition:leave-end="opacity-0 scale-95" 
                                             class="py-1 px-2 w-60 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg" 
                                             x-cloak
                                             style="position: absolute; z-index: 99999;">
                                            <div class="py-1">
                                                <div class="border-b border-white border-opacity-10 pb-2 mb-2">
                                                    <button @click="toggleAllWeeks(true); open = false" class="w-full rounded-lg text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors">
                                                        <i class="fas fa-chevron-down w-5 text-cyan-400"></i>
                                                        <span class="text-sm">Collapse all weeks</span>
                                                    </button>
                                                    <button @click="toggleAllWeeks(false); open = false" class="w-full rounded-lg text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors">
                                                        <i class="fas fa-chevron-up w-5 text-cyan-400"></i>
                                                        <span class="text-sm">Expand all weeks</span>
                                                    </button>
                                                </div>
                                                <button wire:click.prevent="deleteAll" class="w-full rounded-lg text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors">
                                                    <i class="fas fa-trash-alt w-5 text-red-400"></i>
                                                    <span class="text-sm">Delete all the workouts</span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Months -->
                @foreach ($months as $monthKey => $weeksInMonth)
                    @php
                        $monthInfo = $this->getMonthInfo($monthKey);
                        $monthName = $monthInfo['name'];
                        $monthNumber = $monthInfo['number'];
                        $hasMismatch = $monthInfo['hasMismatch'];
                    @endphp
                    <section id="{{ Str::slug($monthName) }}"                         
                            class="mb-4 sm:mb-5" 
                            data-month-key="{{ $monthKey }}" 
                            data-month-number="{{ $monthNumber }}"
                            @if($hasMismatch) style="border: 2px solid red; padding: 1rem;" @endif>
                        <!-- Month header -->
                        <h2>
                            <div class="flex flex-row items-center gap-3 py-2">
                                <span class="text-2xl font-bold text-white ms-2">{{ $monthName }}</span>
                                @if($hasMismatch)
                                    <span class="text-xs text-red-500 font-normal">
                                        Corrected month name
                                    </span>
                                @endif
                                
                                <!-- Mobile Month Controls - Now on same line as month name, visible only on mobile -->
                                <div class="flex sm:hidden items-center gap-2 ml-auto">   
                                    <!-- Month menu contextuel for mobile -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="py-2 px-2 text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        
                                        <!-- Dropdown menu using teleport -->
                                        <template x-teleport="body">
                                            <div x-show="open" 
                                                 x-effect="
                                                    if (open) {
                                                        $nextTick(() => {
                                                            const button = $root.previousElementSibling;
                                                            const rect = button.getBoundingClientRect();
                                                            $el.style.top = `${rect.bottom + window.scrollY + 5}px`;
                                                            
                                                            // Positionnement mobile : décalé vers la gauche avec l'extrémité droite alignée sous le bouton
                                                            if (window.innerWidth < 640) { // sm breakpoint
                                                                $el.style.left = `${rect.right - $el.offsetWidth + 25}px`;
                                                            } else {
                                                                $el.style.left = `${rect.left + rect.width}px`;
                                                            }
                                                        });
                                                    }
                                                 "
                                                 @click.away="open = false" 
                                                 x-transition:enter="transition ease-out duration-200" 
                                                 x-transition:enter-start="opacity-0 scale-95" 
                                                 x-transition:enter-end="opacity-100 scale-100" 
                                                 x-transition:leave="transition ease-in duration-175" 
                                                 x-transition:leave-start="opacity-100 scale-100" 
                                                 x-transition:leave-end="opacity-0 scale-95" 
                                                 class="py-1 px-2 w-60 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg" 
                                                 x-cloak
                                                 style="position: absolute; z-index: 99999;">
                                            <div class="py-1">
                                                <button wire:click.prevent="deleteMonth('{{ $monthKey }}')" class="w-full rounded-lg text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors">
                                                    <i class="fas fa-trash-alt w-5 text-red-400"></i>
                                                    <span class="text-sm">Delete monthly workouts</span>
                                                </button>
                                                <!-- Other actions can be added here later -->
                                            </div>
                                        </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Month stats - Only visible on desktop -->
                                <div class="ml-auto hidden sm:flex cursor-default">
                                    <div class="flex items-center gap-3 px-4 bg-white bg-opacity-10 border-white border-opacity-20 border rounded-lg shadow-lg">                                    
                                        @foreach(['distance', 'duration', 'elevation'] as $stat)
                                            <div class="flex flex-row items-center gap-2 py-2">
                                                <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-400"></i>
                                                <div class="flex items-end">
                                                    <span class="font-semibold text-white">
                                                        @if($stat === 'distance')
                                                            {{ number_format($monthStats[$monthKey]['actual'][$stat], 1) }}
                                                        @elseif($stat === 'duration')
                                                            {{ formatTime((int)($monthStats[$monthKey]['actual'][$stat])) }}
                                                        @else
                                                            {{ $monthStats[$monthKey]['actual'][$stat] }}
                                                        @endif
                                                    </span>
                                                    
                                                    @if($monthStats[$monthKey]['planned'][$stat] > 0)
                                                        <span class="text-xs text-gray-400 ml-1 whitespace-nowrap flex items-end mb-0.5">
                                                            /&nbsp;<span>
                                                                @if($stat === 'duration')
                                                                    {{ formatTime($monthStats[$monthKey]['planned'][$stat]) }}
                                                                @else
                                                                    {{ $stat === 'distance' ? number_format($monthStats[$monthKey]['planned'][$stat], 1) : $monthStats[$monthKey]['planned'][$stat] }}
                                                                @endif
                                                            </span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach                                    
                                        
                                        <div class="relative block" x-data="{ open: false }">
                                            <button @click="open = !open" class="py-3 px-2 text-gray-400 hover:text-white rounded-xl transition-colors focus:outline-none">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            
                                            <!-- Dropdown menu using teleport -->
                                            <template x-teleport="body">
                                                <div x-show="open" 
                                                     x-effect="
                                                        if (open) {
                                                            $nextTick(() => {
                                                                const button = $root.previousElementSibling;
                                                                const rect = button.getBoundingClientRect();
                                                                $el.style.top = `${rect.bottom + window.scrollY + 5}px`;
                                                                $el.style.left = `${rect.left - 210 + rect.width}px`;
                                                            });
                                                        }
                                                     "
                                                     @click.away="open = false" 
                                                     x-transition:enter="transition ease-out duration-200" 
                                                     x-transition:enter-start="opacity-0 scale-95" 
                                                     x-transition:enter-end="opacity-100 scale-100" 
                                                     x-transition:leave="transition ease-in duration-175" 
                                                     x-transition:leave-start="opacity-100 scale-100" 
                                                     x-transition:leave-end="opacity-0 scale-95" 
                                                     class="py-1 px-2 w-60 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg" 
                                                     x-cloak
                                                     style="position: absolute; z-index: 99999;">
                                                <div class="py-1">
                                                    <button wire:click.prevent="deleteMonth('{{ $monthKey }}')" class="w-full rounded-lg text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors">
                                                        <i class="fas fa-trash-alt w-5 text-red-400"></i>
                                                        <span class="text-sm">Delete monthly workouts</span>
                                                    </button>
                                                    <!-- Other actions can be added here later -->
                                                </div>
                                            </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h2>

                        <!-- Weeks -->
                        @foreach ($weeksInMonth as $week)
                            @php
                                $baseColor = $week->type->color ?? 'bg-slate-500';
                                $isDevelopmentWeek = $week->type && strtolower($week->type->name) === 'development';
                                $colorPalette = $this->getWeekColorPalette($baseColor);
                            @endphp
                            <div wire:key="week-{{ $week->id }}" x-data="{
                                collapsed: false,
                                weekId: '{{ $week->id }}',
                                monthKey: '{{ $monthKey }}',
                                year: '{{ $year }}',
                                storageKey() {
                                    return `weekState_${this.year}_${this.weekId}`;
                                },
                                saveState() {
                                    localStorage.setItem(this.storageKey(), this.collapsed ? '1' : '0');
                                }
                            }"
                            x-init="$nextTick(() => { 
                                // Charger l'état depuis le localStorage, défaut à false (déplié)
                                collapsed = localStorage.getItem(storageKey()) === '1'; 
                            })"
                            @toggle-week-collapse.window="if ($event.detail.weekId === weekId) { 
                                collapsed = !collapsed; 
                                saveState();
                            }"                        
                            class="relative rounded-xl shadow-lg ps-2 mb-2 overflow-visible border bg-white bg-opacity-10 border-white border-opacity-20 {{ $week->is_current_week ? 'ring-2 ring-amber-300/60 ring-offset-1 ring-offset-slate-900/40' : '' }}">
                                <!-- Background overlay plus visible -->
                                <div class="absolute inset-0 opacity-20 bg-gradient-to-br from-{{ $colorPalette['lightShade'] }} via-{{ $colorPalette['midShade'] }} to-{{ $colorPalette['darkShade'] }}"></div>
                                
                                <!-- Bande colorée à gauche pour une identification plus marquée -->
                                <div class="absolute left-0 top-0 bottom-0 w-2 bg-{{ $colorPalette['midShade'] }} rounded-l-xl"></div>
                                
                                <!-- Contenu de la semaine -->
                                <div class="relative z-10 week-header pt-2 pb-1 px-2 rounded-t-xl">
                                    <div class="flex flex-col gap-4">
                                        <!-- Week info and controls -->
                                        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                                            <div class="flex justify-between sm:flex-col gap-2">
                                                <div class="flex flex-wrap items-center gap-2 ps-1">
                                                    <span class="hidden sm:block px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray">
                                                        Week {{ $week->week_number }}
                                                    </span>                                        
                                                    <span class="text-sm text-white">
                                                        {{ $week->start }} - {{ $week->end }}
                                                    </span>
                                                </div>
                                            
                                                <div class="flex items-center gap-2">
                                                    <!-- Week Type Button with Dropdown -->
                                                    <div class="relative" x-data="{ typeMenuOpen: false }">
                                                        <button 
                                                            @click="typeMenuOpen = !typeMenuOpen" 
                                                            class="text-white bg-cyan-600 hover:bg-cyan-500 py-1.5 px-3 text-sm rounded-md flex items-center gap-2 w-40">
                                                            <i class="fas fa-tag"></i>
                                                            @if($week->type)
                                                                <span class="flex items-center gap-2">
                                                                    <span class="w-3 h-3 rounded-full {{ $week->type->color }}"></span>
                                                                    {{ $week->type->name }}
                                                                </span>
                                                            @else
                                                                <span>Set week type</span>
                                                            @endif
                                                        </button>
                                                        
                                                        <!-- Week Type Dropdown Menu -->
                                                        <template x-teleport="body">
                                                            <div 
                                                                x-show="typeMenuOpen" 
                                                                x-effect="
                                                                    if (typeMenuOpen) {
                                                                        $nextTick(() => {
                                                                            const button = $root.querySelector('button');
                                                                            const rect = button.getBoundingClientRect();
                                                                            $el.style.top = `${rect.bottom + window.scrollY + 5}px`;
                                                                            $el.style.left = `${rect.left}px`;
                                                                        });
                                                                    }
                                                                "
                                                                @click.away="typeMenuOpen = false" 
                                                                x-transition:enter="transition ease-out duration-200" 
                                                                x-transition:enter-start="opacity-0 scale-95" 
                                                                x-transition:enter-end="opacity-100 scale-100" 
                                                                x-transition:leave="transition ease-in duration-175" 
                                                                x-transition:leave-start="opacity-100 scale-100" 
                                                                x-transition:leave-end="opacity-0 scale-95" 
                                                                class="p-2 w-44 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg" 
                                                                x-cloak
                                                                style="position: absolute; z-index: 99999;">
                                                                <div class="pb-1 max-h-80 overflow-y-auto">
                                                                    <!-- Option to set no week type -->
                                                                    <button 
                                                                        wire:click="setWeekType({{ $week->id }}, null)" 
                                                                        @click="typeMenuOpen = false"
                                                                        class="w-full text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 rounded-lg transition-colors">
                                                                        <span class="w-4 h-4 rounded-full bg-gray-500 bg-opacity-30"></span>
                                                                        <span class="text-sm">None</span>
                                                                    </button>
                                                                    
                                                                    <!-- Divider -->
                                                                    <div class="my-1 border-t border-white border-opacity-10"></div>
                                                                    
                                                                    @foreach($weekTypes as $weekType)
                                                                        <button 
                                                                            wire:click="setWeekType({{ $week->id }}, {{ $weekType->id }})" 
                                                                            @click="typeMenuOpen = false"
                                                                            class="w-full text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 rounded-lg transition-colors">
                                                                            <span class="w-4 h-4 rounded-full {{ $weekType->color }}"></span>
                                                                            <span class="text-sm">{{ $weekType->name }}</span>
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                    
                                                    <!-- Collapse/Expand Button with chevron - Changed from hidden to visible on mobile -->
                                                    <button 
                                                        @click="collapsed = !collapsed; saveState()" 
                                                        class="flex py-1.5 ps-2 items-center justify-center text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none collapse-toggle"
                                                        data-tippy-content="Toggle week">
                                                        <i class="fas" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                                                    </button>
                                                    
                                                    <!-- Week Dropdown Menu Button - Changed from hidden sm:block to block -->
                                                    <div class="relative" x-data="{ open: false }">
                                                        <button @click="open = !open" class="block py-1.5 px-2 text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        
                                                        <!-- Dropdown menu using teleport -->
                                                        <template x-teleport="body">
                                                            <div x-show="open" 
                                                                 x-effect="
                                                                    if (open) {
                                                                        $nextTick(() => {
                                                                            const button = $root.previousElementSibling;
                                                                            const rect = button.getBoundingClientRect();
                                                                            $el.style.top = `${rect.bottom + window.scrollY + 5}px`;
                                                                            
                                                                            // Positionnement mobile : décalé vers la gauche avec l'extrémité droite alignée sous le bouton
                                                                            if (window.innerWidth < 640) { // sm breakpoint
                                                                                $el.style.left = `${rect.right - $el.offsetWidth + 30}px`;
                                                                            } else {
                                                                                $el.style.left = `${rect.left + rect.width}px`;
                                                                            }
                                                                        });
                                                                    }
                                                                 "
                                                                 @click.away="open = false" 
                                                                 x-transition:enter="transition ease-out duration-200" 
                                                                 x-transition:enter-start="opacity-0 scale-95" 
                                                                 x-transition:enter-end="opacity-100 scale-100" 
                                                                 x-transition:leave="transition ease-in duration-175" 
                                                                 x-transition:leave-start="opacity-100 scale-100" 
                                                                 x-transition:leave-end="opacity-0 scale-95" 
                                                                 class="py-1 px-2 w-60 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg" 
                                                                 x-cloak
                                                                 style="position: absolute; z-index: 99999;">
                                                                 <div class="py-1">
                                                                    <button wire:click.prevent="deleteWeek('{{ $week->id }}')" class="w-full text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 rounded-lg transition-colors">
                                                                        <i class="fas fa-trash-alt w-5 text-red-400"></i>
                                                                        <span class="text-sm">Delete weekly workouts</span>
                                                                    </button>
                                                                    <!-- Other actions can be added here later -->
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Week stats -->
                                            <div class="flex justify-between mb-1 sm:gap-4 md:gap-6 cursor-default">
                                                @foreach(['distance', 'duration', 'elevation'] as $stat)
                                                <!-- Structure for all stat displays with consistent height regardless of content -->
                                                    <div class="flex flex-col md:w-36 lg:w-36">
                                                        <!-- Stat increase display -->
                                                        <div class="flex justify-center h-5">
                                                            @if($isDevelopmentWeek && ($stat === 'distance' || $stat === 'duration'))
                                                                @php
                                                                    $progressData = $this->calculateDevelopmentWeekProgress($week, $weeksInMonth, $loop->index);
                                                                    $statProgressData = $progressData[$stat] ?? null;
                                                                @endphp
                                                                
                                                                @if($progressData['isValid'] && isset($statProgressData) && is_array($statProgressData))
                                                                    @php
                                                                        $increase = $statProgressData['value'];
                                                                    @endphp
                                                                    @if($increase > 10)
                                                                        <span class="text-red-400 text-xs font-semibold">
                                                                            <i class="fas fa-exclamation-triangle text-2xs mr-1"></i>+{{ number_format($increase, 1) }}%
                                                                        </span>
                                                                    @elseif($increase != 0)
                                                                        <span class="text-emerald-400 text-xs font-semibold">
                                                                            <i class="fas fa-circle-check text-2xs mr-1"></i>+{{ number_format($increase, 1) }}%
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
                                                                        {{ number_format($week->actual_stats[$stat], 1) }}
                                                                    @elseif($stat === 'duration')
                                                                        {{ formatTime((int)($week->actual_stats[$stat])) }}
                                                                    @else
                                                                        {{ $week->actual_stats[$stat] }}
                                                                    @endif
                                                                </span>
                                                                
                                                                @if($week->planned_stats[$stat] > 0)
                                                                    <span class="text-sm text-gray-400 ml-0.5 whitespace-nowrap flex items-end mb-0.5">
                                                                        /&nbsp;<span>
                                                                            @if($stat === 'duration')
                                                                                {{ formatTime($week->planned_stats[$stat]) }}
                                                                            @else
                                                                                {{ $stat === 'distance' ? number_format($week->planned_stats[$stat], 1) : $week->planned_stats[$stat] }}
                                                                            @endif
                                                                        </span>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Stat progress bar -->
                                                        @if($week->planned_stats[$stat] > 0)                                
                                                            @php 
                                                                $percentage = $this->calculateCompletionPercentage($week->actual_stats[$stat], $week->planned_stats[$stat]);
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
                                </div>
                                <div class="p-2" x-show="!collapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                    <div class="grid grid-cols-2 lg:grid-cols-7 gap-3">
                                        @foreach ($week->days as $day)
                                            @php
                                                $dayDate = $day['date'];
                                                // Détermine si c'est le dernier jour de la semaine
                                                $isLastDayInWeek = $loop->last;
                                                // Détermine si c'est un jour impair dans la liste (pour mobile/tablette)
                                                $isOddDayInList = $loop->iteration % 2 !== 0;
                                                // Si c'est le dernier jour et qu'il est impair (seul sur sa ligne), il devrait prendre toute la largeur
                                                $shouldTakeFullWidth = $isLastDayInWeek && $isOddDayInList;
                                            @endphp
                                            <div wire:key="day-{{ $dayDate->format('Y-m-d') }}"
                                                ondragover="onDragOver(event)" 
                                                ondrop="onDrop(event, '{{ $dayDate->format('Y-m-d') }}')" 
                                                ondragleave="onDragLeave(event)" 
                                                wire:click.stop="$dispatch('openModal', { component: 'workout-modal', attributes: { date: '{{ $dayDate->format('Y-m-d') }}' }})" 
                                                class="relative block p-2 rounded-lg {{ $day['is_today'] ? 'ring-2 ring-amber-300/60 ring-offset-1 ring-offset-slate-900/40' : 'border border-white/20' }} min-h-24 cursor-pointer hover:transform hover:scale-[1.02] transition-all duration-200
                                                shadow-[0_2px_4px_rgba(0,0,0,0.2)] 
                                                hover:shadow-[0_3px_6px_rgba(0,0,0,0.3)] 
                                                bg-gradient-to-b from-slate-800/40 to-slate-900/40
                                                {{ $shouldTakeFullWidth ? 'col-span-2 lg:col-span-1' : '' }}"
                                                style="transform-style: preserve-3d; backface-visibility: hidden;">
                                                <!-- Day date display in calendar cell -->
                                                <div class="absolute top-2 left-2">
                                                    <div>
                                                        <span class="text-sm text-cyan-200 drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]">{{ $day['name'] }}</span>
                                                        <span class="text-sm font-bold text-white drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]">{{ $day['number'] }}</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Completed activities badges - Shows Strava activities for this day -->
                                                @php 
                                                    $dayActivities = $activities->filter(function ($activity) use ($dayDate) {
                                                        return $activity->start_date->isSameDay($dayDate);
                                                    });
                                                @endphp
                                                @if($dayActivities->isNotEmpty())
                                                    <div class="absolute top-2 right-2 flex flex-wrap justify-end gap-1.5">
                                                        @foreach($dayActivities as $activity)
                                                        <div class="relative">
                                                            <a wire:click.stop="$dispatch('openModal', { component: 'activity-modal', attributes: { id: '{{ $activity->id }}' }})" 
                                                                class="relative cursor-pointer block"
                                                                data-tippy-content="{{ $activity->name }}">
                                                                <div class="w-8 h-8 rounded-full flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 text-white 
                                                                    shadow-[0_2px_4px_rgba(0,0,0,0.3)] 
                                                                    hover:shadow-[0_3px_5px_rgba(0,0,0,0.4)] 
                                                                    hover:transform hover:scale-105 active:translate-y-[1px]
                                                                    transition-all duration-150">
                                                                <i class="fas fa-running text-sm drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]"></i>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Workout badges -->
                                                @php 
                                                    $dayWorkouts = $workouts->filter(function ($workout) use ($dayDate){
                                                        return $workout->date->isSameDay($dayDate);
                                                    }); 
                                                @endphp
                                                @if($dayWorkouts->isNotEmpty())
                                                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1.5 max-w-[80%]">
                                                        @foreach($dayWorkouts as $workout)
                                                            <div class="relative">
                                                                <a wire:click.stop="$dispatch('openModal', { component: 'workout-modal', attributes: { id: '{{ $workout->id }}' }})" 
                                                                    class="relative cursor-pointer block"
                                                                    draggable="true" 
                                                                    ondragstart="onDragStart(event, {{ $workout->id }})"
                                                                    data-tippy-content="{{ 
                                                                        ($workout->type ? '<div class=\'font-medium mb-0.5\'>' . $workout->type->name . '</div>' : '<div class=\'font-medium mb-0.5\'>Workout</div>') . 
                                                                        '<div class=\'flex flex-wrap gap-x-2 text-gray-300 text-xs\'>' . 
                                                                            ($workout->duration > 0 ? '<span class=\'whitespace-nowrap\'><i class=\'fas fa-stopwatch mr-1\'></i>' . formatTime($workout->duration * 60) . '</span>' : '') . 
                                                                            ($workout->distance > 0 ? '<span class=\'whitespace-nowrap\'><i class=\'fas fa-route mr-1\'></i>' . formatDistance($workout->distance) . '</span>' : '') .
                                                                            ($workout->elevation > 0 ? '<span class=\'whitespace-nowrap\'><i class=\'fas fa-mountain mr-1\'></i>' . $workout->elevation . 'm</span>' : '') .
                                                                        '</div>' . 
                                                                        ($workout->notes != '' ? '<div class=\'mt-1 text-gray-200 text-xs line-clamp-2\'>' . $workout->notes . '</div>' : '')
                                                                    }}">
                                                                <!-- Workout icon with enhanced 3D effect -->
                                                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $workout->type ? $workout->type->color : 'bg-gray-500' }} text-white 
                                                                    shadow-[0_2px_4px_rgba(0,0,0,0.3)] 
                                                                    hover:shadow-[0_3px_5px_rgba(0,0,0,0.4)] 
                                                                    hover:transform hover:scale-105 hover:translate-y-[-1px] active:translate-y-[1px]
                                                                    transition-all duration-150">
                                                                    @if($workout->type && $workout->type->name === 'Race')
                                                                        <i class="{{ $workout->type->icon }} text-sm drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]"></i>
                                                                    @else
                                                                        <span class="text-sm font-semibold drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]">{{ $workout->type ? $workout->type->short : 'W' }}</span>
                                                                    @endif
                                                                </div>
                                                                <!-- Pulsating dot for new or important workouts (optional) -->
                                                                @if($workout->is_important ?? false)
                                                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500"></span>
                                                                </span>
                                                                @endif
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
                    @endforeach
                </section>
                @endforeach

            </div>

            <!-- Side navigation panel - Fixed position on desktop -->
            <div class="xl:w-52">
                <div class="xl:fixed">
                    <!-- Desktop navigation sidebar - Only visible on large screens -->
                    <div class="hidden lg:block">
                        <div class="bg-white bg-opacity-10 border-white border-opacity-20 border backdrop-blur-lg rounded-xl shadow-lg p-4">
                            <h3 class="font-bold text-white mt-1 pb-4 mb-4 border-b border-white border-opacity-20"><i class="fas fa-map-marker-alt mr-2 text-amber-300"></i>
                                Navigation
                            </h3>
                            <nav class="">
                                @php
                                    $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                                @endphp
                                <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-1 text-slate-100 hover:text-cyan-200 rounded-lg transition-colors cursor-pointer">
                                    Scroll to top
                                </a>
                                @foreach ($months as $monthKey => $weeksInMonth)
                                    @php
                                        if(substr($monthKey, 0, 4) != $year) {
                                            continue;
                                        }
                                        $monthInfo = $this->getMonthInfo($monthKey);
                                        $monthName = $monthInfo['name'];
                                    @endphp
                                    <a href="#{{ Str::slug($monthName) }}" 
                                    class="flex items-center justify-between px-3 py-1 gap-2 rounded-xl text-slate-100 hover:text-cyan-200 transition-all duration-200 group">
                                        <span>{{ $monthName }}</span>
                                        <span class="text-sm text-slate-300">
                                            {{ count($weeksInMonth) }} weeks
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

        /* Tippy.js theme customization */
        .tippy-box[data-theme~='calendar'] {
            background-color: rgba(15, 23, 42, 0.95);
            color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 0.75rem;
            max-width: 350px !important;
            width: max-content !important;
        }

        .tippy-box[data-theme~='calendar'][data-placement^='top'] .tippy-arrow {
            bottom: -7px;
            border-top-color: rgba(15, 23, 42, 0.95);
        }

        .tippy-box[data-theme~='calendar'][data-placement^='bottom'] .tippy-arrow {
            top: -7px;
            border-bottom-color: rgba(15, 23, 42, 0.95);
        }
    </style>
</div>
@script
<script>
    // URL update handling
    Livewire.on('update-url', ({ year }) => {
        const url = new URL(window.location);
        url.pathname = `/calendar/${year}`;
        window.history.pushState(null, '', url);
    });

    // Drag and Drop functionality
    function onDragStart(event, workoutId) {
        event.dataTransfer.setData('text/plain', workoutId);
        event.currentTarget.classList.add('opacity-50');
    }

    function onDragOver(event) {
        event.preventDefault();
        event.currentTarget.classList.add('bg-cyan-600', 'bg-opacity-20');
    }

    function onDragLeave(event) {
        event.currentTarget.classList.remove('bg-cyan-600', 'bg-opacity-20');
    }

    function onDrop(event, date) {
        event.preventDefault();
        const workoutId = event.dataTransfer.getData('text/plain');
        event.currentTarget.classList.remove('bg-cyan-600', 'bg-opacity-20');
        
        // Call Livewire method to update workout date
        Livewire.dispatch('moveWorkout', { workoutId: workoutId, date: date });
    }

    // Initialize Tippy.js tooltips - Event listeners
    document.addEventListener('livewire:navigated', () => initTippyTooltips());
    document.addEventListener('livewire:init', () => initTippyTooltips());
    document.addEventListener('DOMContentLoaded', () => initTippyTooltips());

    // Reinitialize tooltips after Livewire operations
    document.addEventListener('livewire:load', () => {
        Livewire.hook('message.processed', () => {
            setTimeout(() => initTippyTooltips(), 100);
        });
    });
    
    // Reinitialize tooltips on Livewire events
    Livewire.on('reload-tooltips', () => {
        console.log('Reinitializing tooltips');
        // Delay tooltip initialization to ensure DOM is updated
        setTimeout(() => {
            if (typeof tippy !== 'undefined' && typeof tippy.hideAll === 'function') {
                console.log('Destroying existing tooltips');
                tippy.hideAll({ duration: 0 });
            }
            console.log('Creating new tooltips');
            initTippyTooltips();
        }, 500);
    });
    
    function initTippyTooltips() {
        // Destroy existing tooltips first to prevent duplicates
        if (typeof tippy !== 'undefined' && typeof tippy.hideAll === 'function') {
            tippy.hideAll();
        }
        
        // Check if on mobile or tablet (screen < 1024px)
        const isMobileOrTablet = window.innerWidth < 1024;
        
        // Initialize Tippy.js for activity tooltips only on desktop
        if (typeof tippy !== 'undefined' && !isMobileOrTablet) {
            // Activity tooltips (simple text)
            tippy('[data-tippy-content]:not([data-tippy-content*="<"])', {
                theme: 'calendar',
                placement: 'top',
                arrow: true,
                animation: 'scale',
                duration: [200, 100],
                delay: [300, 0],
                offset: [0, 8],
                onShow(instance) {
                    // Dynamically update tooltip content for collapse/expand buttons
                    const element = instance.reference;
                    if (element.classList.contains('collapse-toggle')) {
                        // Check button state for collapse/expand buttons
                        const icon = element.querySelector('i');
                        if (icon.classList.contains('fa-chevron-down')) {
                            instance.setContent('Expand');
                        } else if (icon.classList.contains('fa-chevron-up')) {
                            instance.setContent('Collapse');
                        }
                    }
                }
            });

            // Workout tooltips (HTML content)
            tippy('[data-tippy-content*="<"]', {
                theme: 'calendar',
                placement: 'top',
                arrow: true,
                animation: 'scale',
                allowHTML: true,
                interactive: true,
                duration: [200, 100],
                delay: [300, 0],
                offset: [0, 8]
            });
        } else if (typeof tippy === 'undefined') {
            console.warn('Tippy.js not loaded. Tooltips will not function properly.');
        }
    }
    
    // Add event to check window resize
    window.addEventListener('resize', () => {
        const isMobileOrTablet = window.innerWidth < 1024;
        
        // If transitioning from mobile to desktop, initialize tooltips
        if (!isMobileOrTablet) {
            initTippyTooltips();
        } 
        // If transitioning from desktop to mobile, destroy tooltips
        else if (typeof tippy !== 'undefined' && typeof tippy.hideAll === 'function') {
            tippy.hideAll();
        }
    });

    // Function to collapse or expand all weeks
    function toggleAllWeeks(shouldCollapse) {
        // Get all week elements
        const weekElements = document.querySelectorAll('[wire\\:key^="week-"]');
        
        weekElements.forEach(weekEl => {
            // Get the Alpine component instance for this week
            const weekComponent = Alpine.$data(weekEl);
            
            // Set the collapsed state according to the parameter
            if (weekComponent.collapsed !== shouldCollapse) {
                weekComponent.collapsed = shouldCollapse;
                weekComponent.saveState();
            }
        });
    }

    // Make toggleAllWeeks available to Alpine components
    window.toggleAllWeeks = toggleAllWeeks;
</script>
@endscript