<?php
    use App\http\Helpers\Helpers; 
    use Carbon\Carbon;
?>    

<div class="mx-auto p-2 sm:p-4 overflow-y-scroll relative">
    <!-- Fond d'écran fixe qui couvre toute la page -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 -z-10"></div>

    <!-- Synchronization notification -->
    <div
        wire:loading
        wire:target="startSync"
        class="fixed inset-0 bg-black/70 backdrop-blur-md z-[9999] flex items-center justify-center">
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center p-8 rounded-2xl bg-gradient-to-br from-amber-500/20 to-orange-700/20 border border-amber-500/30 backdrop-blur-lg shadow-2xl max-w-md w-full">
            <div class="relative w-24 h-24 mb-6">
                <!-- External rotating circle -->
                <div class="absolute inset-0 rounded-full border-4 border-amber-500/20"></div>
                <div class="absolute inset-0 rounded-full border-t-4 border-amber-500 animate-spin"></div>
                
                <!-- Strava logo with pulse animation -->
                <div class="absolute inset-0 flex items-center justify-center animate-pulse">
                    <i class="fab fa-strava text-5xl text-amber-500"></i>
                </div>
            </div>
            
            <h3 class="text-2xl font-bold text-white mb-2">Strava Synchronization</h3>
            <p class="text-center text-white/80 mb-6">Retrieving your training data from Strava...</p>
            
            <!-- Animated progress indicator -->
            <div class="w-full h-2 bg-gray-700/50 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full animate-pulse"></div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Button -->
    <div 
        x-data="{ mobileNavOpen: false }" 
        class="xl:hidden fixed top-4 right-4 z-[9999]">
        
        <button 
            @click="mobileNavOpen = true" 
            class="w-11 h-11 text-white bg-cyan-600 hover:bg-cyan-500 rounded-full shadow-lg flex items-center justify-center">
            <i class="fas fa-bars text-lg"></i>
        </button>
        
        <!-- Mobile Menu Overlay -->
        <div 
            x-show="mobileNavOpen" 
            @click.away="mobileNavOpen = false" 
            x-cloak
            class="fixed inset-0 bg-black/50 z-40" 
            style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;">
        </div>
        
        <!-- Mobile Menu Panel -->
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
            
            <div class="absolute top-0 right-0 w-64 bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm h-full shadow-2xl rounded-l-xl transform transition-all duration-300"
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

                    <!-- Navigation -->
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
        <!-- Main content -->
        <div class="flex-1">
            <!-- Global stats -->
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-8">
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
                                        <span class="text-xs text-slate-300 ml-1.5 whitespace-nowrap">
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
                            <div class="flex items-center justify-between gap-2 py-2 px-4 bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl shadow-sm w-full">
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
                            <button wire:click.prevent="startSync" class="relative group py-3 px-4 bg-amber-600 text-white hover:bg-amber-500 rounded-xl hover:bg-amber-600 transition-colors">
                                <i class="fab fa-strava text-2xl" wire:loading.class="animate-spin" wire:target="startSync"></i>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity hidden sm:block">
                                    Synchronize with Strava
                                </div>
                            </button>
                            <button wire:click.prevent="deleteAll" class="hidden sm:block relative group py-3 px-4 bg-red-600 hover:bg-red-500 text-white rounded-xl transition-colors">
                                <i class="fas fa-trash-alt text-2xl"></i>
                                <div class="absolute bottom-full right-0 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50 hidden sm:block">
                                    Delete training sessions for the year
                                </div>
                            </button>
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
                <section id="{{ Str::slug($monthName) }}" class="mb-4 sm:mb-5" 
                        data-month-key="{{ $monthKey }}" 
                        data-month-number="{{ $monthNumber }}"
                        @if($hasMismatch) style="border: 2px solid red; padding: 1rem;" @endif>
                    <!-- Month header -->
                    <h2>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 py-2">
                            <span class="text-2xl font-bold text-white ms-2">{{ $monthName }}</span>
                            @if($hasMismatch)
                                <span class="text-xs text-red-500 font-normal">
                                    Corrected month name
                                </span>
                            @endif
                            
                            <!-- Month stats -->
                            <div class="ml-auto hidden sm:flex">
                                <div class="flex items-center gap-2 px-2 bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border rounded-lg shadow-sm">
                                    <!-- Dropdown Menu Button -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="py-3 px-3.5 text-white rounded-xl transition-colors hover:bg-white hover:bg-opacity-10 focus:outline-none">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        
                                        <!-- Dropdown menu -->
                                        <div x-show="open" 
                                             @click.away="open = false" 
                                             x-transition:enter="transition ease-out duration-200" 
                                             x-transition:enter-start="opacity-0 scale-95" 
                                             x-transition:enter-end="opacity-100 scale-100" 
                                             x-transition:leave="transition ease-in duration-175" 
                                             x-transition:leave-start="opacity-100 scale-100" 
                                             x-transition:leave-end="opacity-0 scale-95" 
                                             class="absolute right-0 mt-2 w-48 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg z-50">
                                            <div class="py-1">
                                                <button wire:click.prevent="deleteMonth('{{ $monthKey }}')" class="w-full text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 transition-colors">
                                                    <i class="fas fa-trash-alt w-5 text-red-400"></i>
                                                    <span>Delete trainings</span>
                                                </button>
                                                <!-- Other actions can be added here later -->
                                            </div>
                                        </div>
                                    </div>
                                    @foreach(['distance', 'duration', 'elevation'] as $stat)
                                        <div class="flex flex-row items-center gap-2 py-2 pe-3">
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
                        @endphp
                        <!-- Week header avec style amélioré et plus marqué -->
                        <div class="relative rounded-xl shadow-lg ps-2 mb-2 overflow-visible border bg-white bg-opacity-10 border-white border-opacity-20">
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
                                                <button 
                                                    wire:click="$dispatch('openModal', { component: 'week-type-modal', attributes: { weekId: {{ $week->id }}, currentTypeId: {{ $week->week_type_id ?? 'null' }} }})"
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
                                                
                                                <!-- Dropdown Menu Button -->
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" class="py-1.5 px-3 text-white bg-cyan-600 hover:bg-cyan-500 rounded-md transition-colors focus:outline-none">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    
                                                    <!-- Dropdown menu -->
                                                    <div x-show="open" 
                                                         @click.away="open = false" 
                                                         x-transition:enter="transition ease-out duration-200" 
                                                         x-transition:enter-start="opacity-0 scale-95" 
                                                         x-transition:enter-end="opacity-100 scale-100" 
                                                         x-transition:leave="transition ease-in duration-175" 
                                                         x-transition:leave-start="opacity-100 scale-100" 
                                                         x-transition:leave-end="opacity-0 scale-95" 
                                                         class="absolute right-0 py-1 px-2 mt-2 w-48 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg z-50">
                                                        <div class="py-1">
                                                            <button wire:click.prevent="deleteWeek('{{ $week->id }}')" class="w-full text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 rounded-lg transition-colors">
                                                                <i class="fas fa-trash-alt w-5 text-red-400"></i>
                                                                <span>Delete trainings</span>
                                                            </button>
                                                            <!-- Other actions can be added here later -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Week stats -->
                                        <div class="flex justify-between mb-1 sm:gap-4 md:gap-6">
                                            @foreach(['distance', 'duration', 'elevation'] as $stat)
                                                <div class="flex flex-col md:w-28 lg:w-36">
                                                    <div class="flex items-center justify-center mb-1 gap-2">
                                                        <p class="text-xs flex items-center">
                                                            <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-400 text-lg mr-1"></i>
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
                                                                <span class="text-2xs text-slate-300 ml-1 whitespace-nowrap flex items-end mb-0.5">
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
                                                    @if($week->planned_stats[$stat] > 0)
                                                        @php 
                                                            $percentage = ($week->actual_stats[$stat] / $week->planned_stats[$stat]) * 100;
                                                            $percentage = min($percentage, 100);
                                                        @endphp
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
                            <!-- Days grid -->
                            <div class="p-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-7 gap-3">
                                    @foreach ($week->days as $day)
                                        @php
                                            $dayDate = $day['date'];
                                        @endphp
                                        <div wire:key="day-{{ $dayDate->format('Y-m-d') }}"
                                            ondragover="onDragOver(event)" 
                                            ondrop="onDrop(event, '{{ $dayDate->format('Y-m-d') }}')" 
                                            ondragleave="onDragLeave(event)" 
                                            wire:click.stop="$dispatch('openModal', { component: 'training-modal', attributes: { date: '{{ $dayDate->format('Y-m-d') }}' }})" 
                                            class="relative block p-2 rounded-lg {{ $day['is_today'] ? 'border border-amber-300 bg-amber-200/10' : 'border border-white/20' }} min-h-24 cursor-pointer">
                                            <!-- Day header -->
                                            <div class="absolute top-2 left-2">
                                                <div>
                                                    <span class="text-sm text-cyan-200">{{ $day['name'] }}</span>
                                                    <span class="text-sm font-bold text-white">{{ $day['number'] }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Activities badges -->
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

                                            <!-- Training badges -->
                                            @php 
                                                $dayTrainings = $trainings->filter(function ($training) use ($dayDate){
                                                    return $training->date->isSameDay($dayDate);
                                                }); 
                                            @endphp
                                            @if($dayTrainings->isNotEmpty())
                                                <div class="absolute bottom-2 left-2 flex flex-wrap gap-1.5 max-w-[80%]">
                                                    @foreach($dayTrainings as $training)
                                                        <div class="relative group">
                                                            <a wire:click.stop="$dispatch('openModal', { component: 'training-modal', attributes: { id: '{{ $training->id }}' }})" 
                                                                class="relative cursor-pointer block"
                                                                draggable="true" 
                                                                ondragstart="onDragStart(event, {{ $training->id }})">
                                                                <div class="w-8 h-8 sm:w-7 sm:h-7 rounded-full flex items-center justify-center {{ $training->type->color }} text-white shadow-md hover:shadow-lg hover:scale-105 transition-all duration-200">
                                                                    <i class="fas fa-{{ $training->type->icon }} text-sm"></i>
                                                                </div>
                                                                <div class="absolute top-full left-1/2 -translate-x-1/2 translate-y-2 px-2.5 py-1.5 rounded bg-gray-800 text-white text-xs font-medium shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-[9999] hidden md:block w-max max-w-[150px]">
                                                                    <div class="font-medium mb-0.5">{{ $training->type->name }}</div>
                                                                    <div class="flex flex-wrap gap-x-2 text-gray-300 text-2xs">
                                                                        @if($training->duration > 0)
                                                                            <span class="whitespace-nowrap"><i class="fas fa-stopwatch mr-1"></i>{{ formatTime($training->duration * 60) }}</span>
                                                                        @endif
                                                                        @if($training->distance > 0)
                                                                            <span class="whitespace-nowrap"><i class="fas fa-route mr-1"></i>{{ formatDistance($training->distance) }}</span>
                                                                        @endif
                                                                        @if($training->elevation > 0)
                                                                            <span class="whitespace-nowrap"><i class="fas fa-mountain mr-1"></i>{{ $training->elevation }}m</span>
                                                                        @endif
                                                                    </div>
                                                                    @if($training->notes != '')
                                                                        <div class="mt-1 text-gray-200 text-2xs line-clamp-2">{{ $training->notes }}</div>
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

        <!-- Side navigation -->
        <div class="xl:w-52">
            <div class="xl:fixed">
                <!-- Desktop sidebar only -->
                <div class="hidden lg:block">
                    <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl shadow-lg p-4">
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



