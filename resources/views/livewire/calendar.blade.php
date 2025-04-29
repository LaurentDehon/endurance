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
                <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-8">
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
                                <x-dropdown trigger-icon="ellipsis-v" trigger-class="py-3 ps-5 px-2 text-2xl" align="right">
                                    <div class="py-1">
                                        <div class="border-b border-white border-opacity-10 pb-2 mb-2">
                                            <x-dropdown-item icon="chevron-down" iconColor="cyan" @click="toggleAllWeeks(true); open = false">
                                                Collapse all weeks
                                            </x-dropdown-item>
                                            <x-dropdown-item icon="chevron-up" iconColor="cyan" @click="toggleAllWeeks(false); open = false">
                                                Expand all weeks
                                            </x-dropdown-item>
                                        </div>
                                        <x-dropdown-item wire:click.prevent="deleteAll" icon="trash-alt" iconColor="red">
                                            Delete all the workouts
                                        </x-dropdown-item>
                                    </div>
                                </x-dropdown>
                                
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
                    @endphp
                    <section id="{{ Str::slug($monthName) }}"                         
                            class="mb-4 sm:mb-5" 
                            data-month-key="{{ $monthKey }}" 
                            data-month-number="{{ $monthNumber }}">
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
                                                Delete monthly workouts
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
                                        <x-dropdown trigger-icon="ellipsis-v" trigger-class="py-3 px-2 text-gray-400 hover:text-white rounded-xl transition-colors focus:outline-none" align="right">
                                            <div class="py-1">
                                                <x-dropdown-item wire:click.prevent="deleteMonth('{{ $monthKey }}')" icon="trash-alt" iconColor="red">
                                                    Delete monthly workouts
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
                            class="relative rounded-xl shadow-lg ps-2 mb-2 overflow-visible bg-white bg-opacity-10 {{ $week->is_current_week ? 'ring-2 ring-amber-300/60 ring-offset-1 ring-offset-slate-900/40' : '' }}">
                                <!-- Background overlay plus visible -->
                                <div class="absolute inset-0 rounded-xl opacity-20 bg-gradient-to-br from-{{ $colorPalette['lightShade'] }} via-{{ $colorPalette['midShade'] }} to-{{ $colorPalette['darkShade'] }}"></div>
                                
                                <!-- Bande colorée à gauche pour une identification plus marquée -->
                                <div class="absolute left-0 top-0 bottom-0 w-2 bg-{{ $colorPalette['midShade'] }} rounded-l-xl"></div>
                                
                                <!-- Contenu de la semaine -->
                                <div class="relative z-10 week-header pt-2 pb-1 px-2">
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
                                                    <x-dropdown trigger="custom" align="left" width="w-44" teleport="true" :autoClose="false">
                                                        <x-slot name="button">
                                                            <button 
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
                                                        </x-slot>
                                                        
                                                        <div class="pb-1 max-h-80 overflow-y-auto">
                                                            <!-- Option to set no week type -->
                                                            <x-dropdown-item wire:click="setWeekType({{ $week->id }}, null)">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="w-4 h-4 rounded-full bg-gray-500 bg-opacity-30"></span>
                                                                    <span>None</span>
                                                                </div>
                                                            </x-dropdown-item>
                                                            
                                                            <!-- Divider -->
                                                            <div class="my-1 border-t border-white border-opacity-10"></div>
                                                            
                                                            @foreach($weekTypes as $weekType)
                                                                <x-dropdown-item wire:click="setWeekType({{ $week->id }}, {{ $weekType->id }})">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="w-4 h-4 rounded-full {{ $weekType->color }}"></span>
                                                                        <span>{{ $weekType->name }}</span>
                                                                    </div>
                                                                </x-dropdown-item>
                                                            @endforeach
                                                        </div>
                                                    </x-dropdown>
                                                    
                                                    <!-- Collapse/Expand Button with chevron -->
                                                    <button 
                                                        @click="collapsed = !collapsed; saveState()" 
                                                        class="flex py-1.5 ps-2 items-center justify-center text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none collapse-toggle"
                                                        data-tippy-content="Toggle week">
                                                        <i class="fas" :class="collapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                                                    </button>

                                                    <x-dropdown trigger-icon="ellipsis-v" trigger-class="py-1.5 px-2 text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none" align="right">
                                                        <div class="py-1">
                                                            <x-dropdown-item wire:click.prevent="deleteWeek('{{ $week->id }}')" icon="trash-alt" iconColor="red">
                                                                Delete weekly workouts
                                                            </x-dropdown-item>
                                                        </div>
                                                    </x-dropdown>
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
                                                wire:click.stop="$dispatch('openModal', { component: 'modal.workout-modal', attributes: { date: '{{ $dayDate->format('Y-m-d') }}' }})" 
                                                class="relative block p-2 rounded-xl shadow-lg {{ $day['is_today'] ? 'ring-2 ring-amber-300/60 ring-offset-1 ring-offset-slate-900/40' : '' }} min-h-24 cursor-pointer 
                                                bg-gradient-to-b from-slate-800/40 to-slate-900/40 {{ $shouldTakeFullWidth ? 'col-span-2 lg:col-span-1' : '' }}">
                                                <!-- Day date display in calendar cell -->
                                                <div class="absolute top-2 left-2">
                                                    <div>
                                                        <span class="text-sm text-cyan-200">{{ $day['name'] }}</span>
                                                        <span class="text-sm font-bold text-white">{{ $day['number'] }}</span>
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
                                                            <a wire:click.stop="$dispatch('openModal', { component: 'modal.activity-modal', attributes: { id: '{{ $activity->id }}' }})" 
                                                                class="relative cursor-pointer block"
                                                                data-tippy-content="{{ $activity->name }}">
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
                                                    $dayWorkouts = $workouts->filter(function ($workout) use ($dayDate){
                                                        return $workout->date->isSameDay($dayDate);
                                                    }); 
                                                @endphp
                                                @if($dayWorkouts->isNotEmpty())
                                                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1.5 max-w-[80%]">
                                                        @foreach($dayWorkouts as $workout)
                                                            <div class="relative">
                                                                <a wire:click.stop="$dispatch('openModal', { component: 'modal.workout-modal', attributes: { id: '{{ $workout->id }}' }})" 
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
                                                                <!-- Workout icon -->
                                                                <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $workout->type ? $workout->type->color : 'bg-gray-500' }} text-white">
                                                                    @if($workout->type && $workout->type->name === 'Race')
                                                                        <i class="{{ $workout->type->icon }} text-sm"></i>
                                                                    @else
                                                                        <span class="text-sm font-semibold">{{ $workout->type ? $workout->type->short : 'W' }}</span>
                                                                    @endif
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
                    @endforeach
                </section>
                @endforeach

            </div>

            <!-- Side navigation panel - Fixed position on desktop -->
            <div class="xl:w-52">
                <div class="xl:fixed">
                    <!-- Desktop navigation sidebar - Only visible on large screens -->
                    <div class="hidden lg:block">
                        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg p-4">
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
<script>
// --- Drag & Drop & Tippy Module ---
document.addEventListener('DOMContentLoaded', function() {
    // Make sure Livewire is available before creating the CalendarUI
    if (typeof Livewire === 'undefined') {
        // Wait for Livewire to load
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
                    console.error('Error during drop:', error);
                }
            }
            
            // Expose drag functions globally for blade inline events
            window.onDragStart = onDragStart;
            window.onDragOver = onDragOver;
            window.onDragLeave = onDragLeave;
            window.onDrop = function(event, date) { return onDropWrapper(event); };

            // Tippy Tooltips
            var tippyInstances = [];
            function initTippyTooltips(targets) {
                if (typeof tippy === 'undefined' || window.innerWidth < 1024) return;
                // Only initialize on new elements
                var selector = targets || '[data-tippy-content]';
                document.querySelectorAll(selector).forEach(function(el) {
                    if (!el._tippy) {
                        var opts = {
                            theme: 'calendar',
                            placement: 'top',
                            arrow: true,
                            animation: 'scale',
                            duration: [200, 100],
                            delay: [300, 0],
                            offset: [0, 8]
                        };
                        
                        var content = el.getAttribute('data-tippy-content');
                        if (content && content.includes('<')) {
                            opts.allowHTML = true;
                            opts.interactive = true;
                        } else {
                            opts.onShow = function(instance) {
                                var element = instance.reference;
                                if (element.classList.contains('collapse-toggle')) {
                                    var icon = element.querySelector('i');
                                    if (icon && icon.classList.contains('fa-chevron-down')) instance.setContent('Expand');
                                    else if (icon && icon.classList.contains('fa-chevron-up')) instance.setContent('Collapse');
                                }
                            };
                        }
                        tippyInstances.push(tippy(el, opts));
                    }
                });
            }
            
            // MutationObserver for new tooltips
            if (typeof MutationObserver !== 'undefined') {
                var observer = new MutationObserver(function(mutations) {
                    for (var i = 0; i < mutations.length; i++) {
                        var m = mutations[i];
                        if (m.addedNodes) {
                            m.addedNodes.forEach(function(node) {
                                if (node.nodeType === 1 && node.hasAttribute && node.hasAttribute('data-tippy-content')) {
                                    initTippyTooltips('[data-tippy-content]');
                                }
                            });
                        }
                    }
                });
                observer.observe(document.body, { childList: true, subtree: true });
            }
            
            // Livewire/Alpine hooks
            document.addEventListener('livewire:navigated', function() { initTippyTooltips(); });
            document.addEventListener('livewire:init', function() { initTippyTooltips(); });
            document.addEventListener('DOMContentLoaded', function() { initTippyTooltips(); });
            
            if (typeof Livewire !== 'undefined') {
                Livewire.on('reload-tooltips', function() { setTimeout(function() { initTippyTooltips(); }, 500); });
                
                // URL update
                Livewire.on('update-url', function(params) {
                    var year = params.year;
                    var url = new URL(window.location);
                    url.pathname = '/calendar/' + year;
                    window.history.pushState(null, '', url);
                });
            }
            
            window.addEventListener('resize', function() {
                if (window.innerWidth < 1024 && typeof tippy !== 'undefined') tippy.hideAll();
                else initTippyTooltips();
            });
            
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
            
            // Initialize tooltips when setup is complete
            initTippyTooltips();
            
            return { 
                onDragStart: onDragStart, 
                onDragOver: onDragOver, 
                onDragLeave: onDragLeave, 
                onDropWrapper: onDropWrapper, 
                initTippyTooltips: initTippyTooltips 
            };
        })();
    }
});
</script>