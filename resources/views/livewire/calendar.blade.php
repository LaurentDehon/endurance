<?php
    use App\http\Helpers\Helpers; 
    use Carbon\Carbon;
?>    

<div class="mx-auto p-2 sm:p-4 overflow-y-scroll relative">
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
                                
                                try {
                                    // Parse the month key to get the correct month
                                    list($yearPart, $monthPart) = explode('-', $monthKey);
                                    
                                    // Get correct month name using PHP's date function directly
                                    $monthNumber = (int)$monthPart;
                                    $monthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
                                } catch (\Exception $e) {
                                    $monthName = "Month $monthKey";
                                }
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
                    <div class="hidden sm:flex justify-between lg:justify-start gap-4 lg:gap-16">
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
                        <div class="relative flex-grow" x-data="{ 
                            isLoading: false
                        }" 
                        x-on:livewire:navigating="isLoading = true"
                        x-on:livewire:navigated="isLoading = false">
                            <div class="flex items-center justify-between gap-2 py-2 px-4 bg-white bg-opacity-10 border-white border-opacity-20 rounded-xl shadow-lg w-full">
                                <button 
                                    wire:click="previousYear" 
                                    type="button"
                                    :disabled="isLoading"
                                    :class="{ 'opacity-30 cursor-not-allowed': isLoading }"
                                    class="flex items-center justify-center w-10 h-10 text-white bg-cyan-600 hover:bg-cyan-500 bg-opacity-70 rounded-lg hover:bg-opacity-100 transition-all transform">
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
                                    class="flex items-center justify-center w-10 h-10 text-white bg-cyan-600 hover:bg-cyan-500 bg-opacity-70 rounded-lg hover:bg-opacity-100 transition-all transform">
                                    <span x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                    <i x-show="!isLoading" class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex gap-2 flex-shrink-0">
                            <button wire:click.prevent="startSync" class="relative group py-3 px-4 bg-amber-600 text-white hover:bg-amber-500 rounded-xl transition-colors">
                                <i class="fab fa-strava text-2xl" wire:loading.class="animate-spin" wire:target="startSync"></i>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity hidden sm:block">
                                    Synchronize with Strava
                                </div>
                            </button>
                            
                            <!-- Collapse/Expand Year Button with chevron -->
                            <div class="relative block" x-data="{ allYearCollapsed: false }">
                                <button 
                                    @click="allYearCollapsed = !allYearCollapsed; $dispatch(allYearCollapsed ? 'collapse-all-year' : 'expand-all-year')" 
                                    class="pt-4 ps-2 text-gray-400 hover:text-white rounded-xl transition-colors focus:outline-none">
                                    <i class="fas" :class="allYearCollapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                                </button>
                            </div>
                            
                            <!-- Year Options Dropdown Menu Button -->
                            <div class="relative block" x-data="{ open: false }">
                                <button @click="open = !open" class="py-3 px-2 text-gray-400 hover:text-white rounded-xl transition-colors focus:outline-none">
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
                                            <button wire:click.prevent="deleteAll" class="w-full rounded-lg text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors">
                                                <i class="fas fa-trash-alt w-5 text-red-400"></i>
                                                <span class="text-sm">Delete yearly workouts</span>
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
                    try {
                        // Parse the month key to get the correct month
                        list($yearPart, $monthPart) = explode('-', $monthKey);
                        
                        // Create the correct date object
                        $monthDate = Carbon::createFromDate($yearPart, (int)$monthPart, 1);
                        $monthName = $monthDate->format('F');
                        
                        // For validation and debugging
                        $monthNumber = (int)$monthPart; 
                        $expectedMonthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
                        
                        // Double check if month name matches the expected month
                        $hasMismatch = ($monthName !== $expectedMonthName);
                        
                        // Force correct month name if needed
                        if ($hasMismatch) {
                            $monthName = $expectedMonthName;
                        }
                    } catch (\Exception $e) {
                        // Fallback in case of parsing error
                        $monthName = "Month $monthKey";
                        $hasMismatch = true;
                        $monthNumber = 0;
                    }
                @endphp
                <section id="{{ Str::slug($monthName) }}" 
                        x-data="{ 
                            monthCollapsed: false,
                            monthKey: '{{ $monthKey }}' 
                        }"
                        @toggle-month-collapse.window="if ($event.detail.monthKey === monthKey) { 
                            monthCollapsed = !monthCollapsed; 
                            // Dispatch events to all weeks in this month to collapse/expand
                            if (monthCollapsed) {
                                $dispatch('collapse-all-weeks', { monthKey: monthKey });
                            } else {
                                $dispatch('expand-all-weeks', { monthKey: monthKey });
                            }
                        }"
                        @collapse-all-year.window="monthCollapsed = true"
                        @expand-all-year.window="monthCollapsed = false"
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
                                <!-- Month collapse/expand Button with chevron for mobile -->
                                <button 
                                    @click="
                                        monthCollapsed = !monthCollapsed; 
                                        if (monthCollapsed) {
                                            $dispatch('collapse-all-weeks', { monthKey: monthKey });
                                        } else {
                                            $dispatch('expand-all-weeks', { monthKey: monthKey });
                                        }
                                    "
                                    x-effect="
                                        // Mettre à jour l'icône du bouton quand monthCollapsed change
                                        const icon = $el.querySelector('i');
                                        if (icon) {
                                            if (monthCollapsed) {
                                                icon.classList.add('fa-chevron-down');
                                                icon.classList.remove('fa-chevron-up');
                                            } else {
                                                icon.classList.add('fa-chevron-up');
                                                icon.classList.remove('fa-chevron-down');
                                            }
                                        }
                                    "
                                    class="py-2 px-2 text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none">
                                    <i class="fas" :class="monthCollapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                                </button>
                                
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
                            <div class="ml-auto hidden sm:flex">
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
                                    <!-- Month collapse/expand Button with chevron - Changed from hidden to visible on mobile -->
                                    <button 
                                        @click="
                                            monthCollapsed = !monthCollapsed; 
                                            if (monthCollapsed) {
                                                $dispatch('collapse-all-weeks', { monthKey: monthKey });
                                            } else {
                                                $dispatch('expand-all-weeks', { monthKey: monthKey });
                                            }
                                        "
                                        x-effect="
                                            // Mettre à jour l'icône du bouton quand monthCollapsed change
                                            const icon = $el.querySelector('i');
                                            if (icon) {
                                                if (monthCollapsed) {
                                                    icon.classList.add('fa-chevron-down');
                                                    icon.classList.remove('fa-chevron-up');
                                                } else {
                                                    icon.classList.add('fa-chevron-up');
                                                    icon.classList.remove('fa-chevron-down');
                                                }
                                            }
                                        "
                                        class="py-3 ps-2 text-gray-400 hover:text-white rounded-xl transition-colors focus:outline-none block">
                                        <i class="fas" :class="monthCollapsed ? 'fa-chevron-down' : 'fa-chevron-up'"></i>
                                    </button>
                                    
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
                            $color = str_replace('bg-', '', $baseColor);
                            
                            // Extraire la teinte de couleur et la luminosité
                            preg_match('/(.*)-(\d{3})$/', $color, $matches);
                            $colorName = $matches[1] ?? 'slate';
                            $colorWeight = isset($matches[2]) ? intval($matches[2]) : 500;
                            
                            // Créer une palette de couleurs plus marquée
                            $darkShade = $colorName . '-' . min(900, $colorWeight + 200);
                            $midShade = $color;
                            $lightShade = $colorName . '-' . max(100, $colorWeight - 200);
                            
                            // Effet de bande colorée sur le côté gauche
                            $borderColor = $colorName . '-' . min(500, $colorWeight);
                            
                            // Vérifier si c'est une semaine de développement
                            $isDevelopmentWeek = $week->type && strtolower($week->type->name) === 'development';
                            
                            // Variables pour stocker les stats de la semaine précédente
                            $prevWeekPlannedDistance = null;
                            $prevWeekPlannedDuration = null;
                            $distanceIncrease = null;
                            $durationIncrease = null;
                            
                            // Si c'est une semaine de développement, chercher la semaine précédente
                            if ($isDevelopmentWeek) {
                                // Récupérer l'index actuel de la semaine dans le mois
                                $currentIndex = $loop->index;
                                
                                // Si ce n'est pas la première semaine du mois
                                if ($currentIndex > 0) {
                                    $prevWeek = $weeksInMonth[$currentIndex - 1];
                                    $isPrevDevelopmentWeek = $prevWeek->type && strtolower($prevWeek->type->name) === 'development';
                                    
                                    // Si la semaine précédente est aussi une semaine de développement
                                    if ($isPrevDevelopmentWeek) {
                                        $prevWeekPlannedDistance = $prevWeek->planned_stats['distance'];
                                        $prevWeekPlannedDuration = $prevWeek->planned_stats['duration'];
                                        
                                        // Calculer les pourcentages d'augmentation
                                        if ($prevWeekPlannedDistance > 0 && $week->planned_stats['distance'] > 0) {
                                            $distanceIncrease = (($week->planned_stats['distance'] - $prevWeekPlannedDistance) / $prevWeekPlannedDistance) * 100;
                                        }
                                        
                                        if ($prevWeekPlannedDuration > 0 && $week->planned_stats['duration'] > 0) {
                                            $durationIncrease = (($week->planned_stats['duration'] - $prevWeekPlannedDuration) / $prevWeekPlannedDuration) * 100;
                                        }
                                    }
                                }
                            }
                        @endphp
                        <!-- Week header avec style amélioré et plus marqué -->
                        <div x-data="{ 
                                collapsed: false,
                                weekId: '{{ $week->id }}',
                                monthKey: '{{ $monthKey }}'
                             }"
                             @toggle-week-collapse.window="if ($event.detail.weekId === weekId) collapsed = !collapsed"
                             @collapse-all-weeks.window="if ($event.detail.monthKey === monthKey) collapsed = true"
                             @expand-all-weeks.window="if ($event.detail.monthKey === monthKey) collapsed = false"
                             @collapse-all-year.window="collapsed = true"
                             @expand-all-year.window="collapsed = false"
                             class="relative rounded-xl shadow-lg ps-2 mb-2 overflow-visible border bg-white bg-opacity-10 border-white border-opacity-20">
                            <!-- Background overlay plus visible -->
                            <div class="absolute inset-0 opacity-20 bg-gradient-to-br from-{{ $lightShade }} via-{{ $midShade }} to-{{ $darkShade }}"></div>
                            
                            <!-- Bande colorée à gauche pour une identification plus marquée -->
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-{{ $midShade }} rounded-l-xl"></div>
                            
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
                                                        class="text-white bg-cyan-600 hover:bg-cyan-500 py-1.5 px-3 text-sm rounded-md flex items-center gap-2">
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
                                                    @click="collapsed = !collapsed" 
                                                    class="flex py-1.5 ps-2 items-center justify-center text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none">
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
                                        <div class="flex justify-between mb-1 sm:gap-4 md:gap-6">
                                            @foreach(['distance', 'duration', 'elevation'] as $stat)
                                                <div class="flex flex-col md:w-36 lg:w-36">
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
                                                    
                                                    <!-- Structure for all stat displays with consistent height regardless of content -->
                                                    <div class="flex justify-center h-5"> <!-- Fixed height container for increase percentage -->
                                                        @if($isDevelopmentWeek && ($stat === 'distance' || $stat === 'duration'))
                                                            @php
                                                                $increase = $stat === 'distance' ? $distanceIncrease : $durationIncrease;
                                                            @endphp
                                                            
                                                            @if($increase !== null)
                                                                @php
                                                                    // Déterminer la couleur et l'icône en fonction du pourcentage
                                                                    if ($increase > 10) {
                                                                        // Augmentation > 10% => Trop élevé (warning rouge)
                                                                        $increaseColor = 'text-red-400';
                                                                        $increaseIcon = 'fa-exclamation-triangle';
                                                                        $increaseStatus = 'Significant Increase';
                                                                        $increaseMessage = 'An increase greater than 10% may increase the risk of injury.';
                                                                    } elseif ($increase < 0) {
                                                                        // Diminution < 0% => Pas idéal (warning orange)
                                                                        $increaseColor = 'text-amber-400';
                                                                        $increaseIcon = 'fa-exclamation-circle';
                                                                        $increaseStatus = 'Significant Decrease';
                                                                        $increaseMessage = 'A significant decrease may affect training progression and consistency.';
                                                                    } elseif ($increase == 0) {
                                                                        // Pas de changement => Idéal (vert)
                                                                        $increaseColor = 'text-emerald-400';
                                                                        $increaseIcon = 'fa-check-circle';
                                                                        $increaseStatus = 'No Change';
                                                                        $increaseMessage = 'No change is ideal for maintaining a steady training load.';
                                                                    } elseif ($increase > 0 && $increase <= 10) {
                                                                        // Entre 0% et +10% => Idéal (vert)
                                                                        $increaseColor = 'text-emerald-400';
                                                                        $increaseIcon = 'fa-check-circle';
                                                                        $increaseStatus = 'Ideal Progression';
                                                                        $increaseMessage = 'A increase below +10% is recommended for safe progression.';
                                                                    }
                                                                @endphp
                                                                
                                                                <span class="relative group cursor-default sm:cursor-help flex items-start">
                                                                    <span class="{{ $increaseColor }} text-xs font-semibold">
                                                                        <i class="fas {{ $increaseIcon }} text-2xs mr-1"></i>{{ number_format($increase, 1) }}%
                                                                    </span>
                                                                    
                                                                    <!-- Tooltip explicatif -->
                                                                    <div class="hidden sm:block absolute bottom-full left-1/2 -translate-x-1/2 -translate-y-1 px-3 py-2 rounded bg-gray-800 text-white text-xs whitespace-normal shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-[9999] w-48">
                                                                        <p class="mb-1 font-medium">
                                                                            <i class="fas {{ $increaseIcon }} {{ $increaseColor }} mr-1"></i>{{ $increaseStatus }}
                                                                        </p>
                                                                        <p class="text-gray-300 text-2xs">
                                                                            {{ $increase >= 0 ? 'Increase' : 'Decrease' }} of {{ number_format(abs($increase), 1) }}% compared to previous week.
                                                                            <span class="block mt-1 {{ $increaseColor }}">{{ $increaseMessage }}</span>
                                                                        </p>
                                                                    </div>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    
                                                    @if($week->planned_stats[$stat] > 0)                                
                                                        @php 
                                                            $percentage = ($week->actual_stats[$stat] / $week->planned_stats[$stat]) * 100;
                                                            $percentage = min($percentage, 100);
                                                        @endphp
                                                        <!-- Progress bar showing completion percentage of planned stats -->
                                                        <div class="w-full h-2 bg-gray-800 bg-opacity-50 rounded-full">
                                                            <div class="h-2 bg-{{ $statColors[$stat] }}-500 rounded-full" 
                                                                style="width: {{ $percentage }}%"></div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Days grid - Calendar view organized by days of the week -->
                            <div class="p-2" x-show="!collapsed" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3">
                                    @foreach ($week->days as $day)
                                        @php
                                            $dayDate = $day['date'];
                                        @endphp
                                        <div wire:key="day-{{ $dayDate->format('Y-m-d') }}"
                                            ondragover="onDragOver(event)" 
                                            ondrop="onDrop(event, '{{ $dayDate->format('Y-m-d') }}')" 
                                            ondragleave="onDragLeave(event)" 
                                            wire:click.stop="$dispatch('openModal', { component: 'workout-modal', attributes: { date: '{{ $dayDate->format('Y-m-d') }}' }})" 
                                            class="relative block p-2 rounded-lg {{ $day['is_today'] ? 'border border-amber-300 bg-amber-200/10' : 'border border-white/20' }} min-h-24 cursor-pointer">
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
                                                    <div class="relative group">
                                                        <a wire:click.stop="$dispatch('openModal', { component: 'activity-modal', attributes: { id: '{{ $activity->id }}' }})" 
                                                            class="relative cursor-pointer block">
                                                            <div class="w-8 h-8 sm:w-7 sm:h-7 rounded-full flex items-center justify-center bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
                                                                <i class="fas fa-running text-sm"></i>
                                                            </div>
                                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 -translate-y-2 px-2.5 py-1.5 rounded bg-gray-800 text-white text-xs font-medium shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-[9999] hidden md:block">
                                                                {{ $activity->name }}
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
                                                        <div class="relative group">
                                                            <a wire:click.stop="$dispatch('openModal', { component: 'workout-modal', attributes: { id: '{{ $workout->id }}' }})" 
                                                                class="relative cursor-pointer block"
                                                                draggable="true" 
                                                                ondragstart="onDragStart(event, {{ $workout->id }})">
                                                                <!-- Workout icon with drag and drop functionality -->
                                                                <div class="w-8 h-8 sm:w-7 sm:h-7 rounded-full flex items-center justify-center {{ $workout->type->color }} text-white shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
                                                                    <i class="fas fa-{{ $workout->type->icon }} text-sm"></i>
                                                                </div>
                                                                <!-- Workout details tooltip - Appears on hover -->
                                                                <div class="absolute top-full left-1/2 -translate-x-1/2 translate-y-2 px-2.5 py-1.5 rounded bg-gray-800 text-white text-xs font-medium shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-[9999] hidden md:block w-max max-w-[150px]">
                                                                    <div class="font-medium mb-0.5">{{ $workout->type->name }}</div>
                                                                    <div class="flex flex-wrap gap-x-2 text-gray-300 text-2xs">
                                                                        @if($workout->duration > 0)
                                                                            <span class="whitespace-nowrap"><i class="fas fa-stopwatch mr-1"></i>{{ formatTime($workout->duration * 60) }}</span>
                                                                        @endif
                                                                        @if($workout->distance > 0)
                                                                            <span class="whitespace-nowrap"><i class="fas fa-route mr-1"></i>{{ formatDistance($workout->distance) }}</span>
                                                                        @endif
                                                                        @if($workout->elevation > 0)
                                                                            <span class="whitespace-nowrap"><i class="fas fa-mountain mr-1"></i>{{ $workout->elevation }}m</span>
                                                                        @endif
                                                                    </div>
                                                                    @if($workout->notes != '')
                                                                        <div class="mt-1 text-gray-200 text-2xs line-clamp-2">{{ $workout->notes }}</div>
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
                                    
                                    try {
                                        // Parse the month key to get the correct month
                                        list($yearPart, $monthPart) = explode('-', $monthKey);
                                        
                                        // Get correct month name using PHP's date function directly
                                        $monthNumber = (int)$monthPart;
                                        $monthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
                                    } catch (\Exception $e) {
                                        $monthName = "Month $monthKey";
                                    }
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
    </style>
    
    <!-- Confirmation dialog component for delete operations -->
    <livewire:confirmation-modal />
</div>
@script
<script>
    Livewire.on('update-url', ({ year }) => {
        const url = new URL(window.location);
        url.pathname = `/calendar/${year}`;
        window.history.pushState(null, '', url);
    });    
</script>
@endscript