<?php
    use App\http\Helpers\Helpers; 
    use Carbon\Carbon;
?>    

<div class="mx-auto p-2 sm:p-4 overflow-y-scroll relative">
    <!-- Fond d'écran fixe qui couvre toute la page -->
    <div class="fixed inset-0 bg-gradient-to-br {{ themeClass('background') }} -z-10"></div>

    <!-- Mobile Navigation Button -->
    <div 
        x-data="{ mobileNavOpen: false }" 
        class="xl:hidden fixed top-4 right-4 z-[9999]">
        
        <button 
            @click="mobileNavOpen = true" 
            class="w-11 h-11 {{ themeClass('button') }} rounded-full shadow-lg flex items-center justify-center">
            <i class="fas fa-bars text-lg"></i>
        </button>
        
        <!-- Mobile Menu Overlay -->
        <div 
            x-show="mobileNavOpen" 
            @click.away="mobileNavOpen = false" 
            class="fixed inset-0 bg-black/50 z-40" 
            style="position: fixed; top: 0; left: 0; right: 0; bottom: 0;"
            x-cloak>
        </div>
        
        <!-- Mobile Menu Panel -->
        <div 
            x-show="mobileNavOpen"
            class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak
            @click.away="mobileNavOpen = false">
            
            <div class="absolute top-0 right-0 w-64 {{ themeClass('card') }} h-full shadow-2xl rounded-l-xl transform transition-all duration-300"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-250"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full">
                
                <div class="p-4 relative h-full overflow-y-auto">
                    <!-- Header -->
                    <div class="flex justify-between items-center pb-4 mb-4 border-b {{ themeClass('divider') }}">
                        <h3 class="font-bold {{ themeClass('text-1') }} mb-3 mt-5"><i class="fas fa-map-marker-alt mr-2 {{ themeClass('text-accent') }}"></i>
                            Navigation
                        </h3>
                        <button @click="mobileNavOpen = false" 
                                class="p-2.5 hover:bg-gray-100 rounded-lg {{ themeClass('text-2') }} hover:{{ themeClass('text-1') }} transition-colors"
                                aria-label="Close menu">
                            <i class="fas fa-times fa-lg"></i>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav class="space-y-1">
                        @php
                            $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                        @endphp
                        <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-1 {{ themeClass('mobile-nav') }} rounded-lg transition-colors cursor-pointer">
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
                            class="flex items-center justify-between px-3 py-1 rounded-xl {{ themeClass('mobile-nav') }} transition-all duration-200 group">
                                <span>{{ $monthName }}</span>
                                <span class="text-sm {{ themeClass('text-3') }} group-hover:{{ themeClass('text-2') }}">
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
            <div class="{{ themeClass('card') }} backdrop-blur-lg rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-8">
                <!-- Stats section -->
                <div class="flex flex-col lg:flex-row gap-4 sm:gap-8">
                    <!-- Stats wrapper -->
                    <div class="hidden sm:flex justify-between lg:justify-start gap-4 lg:gap-16">
                        @foreach(['distance', 'elevation', 'time'] as $stat)
                        <div class="flex flex-row items-start gap-3 w-full sm:w-auto">
                            <div class="py-3 px-4 bg-{{ $statColors[$stat] }}-100 rounded-xl">
                                <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-600 text-2xl"></i>
                            </div>
                            <div class="text-center">
                                <p class="text-sm {{ themeClass('text-3') }} mb-1">{{ ucfirst($stat) }}</p>
                                <div class="flex flex-col items-center gap-2">
                                    <p class="text-2xl font-bold {{ themeClass('text-1') }}">
                                        @if($stat === 'distance')
                                            {{ number_format($yearStats['actual'][$stat], 0, ',', '') }}
                                        @elseif($stat === 'time')
                                            {{ formatTime((int)($yearStats['actual'][$stat])) }}
                                        @else
                                            {{ number_format($yearStats['actual'][$stat], 0, ',', '') }}
                                        @endif
                                        
                                        @if($yearStats['planned'][$stat] > 0)
                                            <span class="text-sm {{ themeClass('text-3') }}">/ 
                                                @if($stat === 'time')
                                                    {{ formatTime($yearStats['planned'][$stat]) }}
                                                @else
                                                    {{ $stat === 'distance' ? number_format($yearStats['planned'][$stat], 1) : $yearStats['planned'][$stat] }}
                                                @endif
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Controls wrapper -->
                    <div class="flex flex-row justify-between lg:justify-end lg:ml-auto gap-4 items-center">
                        <div class="relative" x-data="{ 
                            years: @js($years),
                            currentYearIndex: @js($years->search(function($yearValue) use ($year) { return $yearValue == $year; })),
                            selectedYear: @entangle('year').defer,
                            isLoading: false,
                            
                            previousYear() {
                                if (this.currentYearIndex > 0 && !this.isLoading) {
                                    this.isLoading = true;
                                    this.currentYearIndex--;
                                    this.selectedYear = this.years[this.currentYearIndex];
                                    this.$wire.setYear(this.selectedYear);
                                }
                            },
                            
                            nextYear() {
                                if (this.currentYearIndex < this.years.length - 1 && !this.isLoading) {
                                    this.isLoading = true;
                                    this.currentYearIndex++;
                                    this.selectedYear = this.years[this.currentYearIndex];
                                    this.$wire.setYear(this.selectedYear);
                                }
                            }
                        }" 
                        x-init="selectedYear = @js($year)"
                        @livewire:navigating="isLoading = true"
                        @livewire:navigated="isLoading = false">
                            <div class="flex items-center justify-center gap-2 py-2 px-2 {{ themeClass('card') }} rounded-xl shadow-sm">
                                <button 
                                    @click="previousYear()" 
                                    type="button"
                                    :disabled="isLoading || currentYearIndex === 0"
                                    :class="{ 'opacity-30 cursor-not-allowed': isLoading || currentYearIndex === 0 }"
                                    class="flex items-center justify-center w-10 h-10 {{ themeClass('button') }} bg-opacity-70 rounded-lg hover:bg-opacity-100 transition-all transform">
                                    <span x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                    <i x-show="!isLoading" class="fas fa-chevron-left"></i>
                                </button>
                                
                                <div class="flex items-center justify-center min-w-[100px] gap-3 py-2 px-4 relative">
                                    <span x-show="isLoading" class="absolute top-0 right-0 -mr-1 -mt-1 w-3 h-3">
                                        <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                    </span>
                                    <span class="font-bold text-2xl {{ themeClass('text-1') }}" x-text="selectedYear"></span>
                                </div>
                                
                                <button 
                                    @click="nextYear()" 
                                    type="button"
                                    :disabled="isLoading || currentYearIndex === years.length - 1"
                                    :class="{ 'opacity-30 cursor-not-allowed': isLoading || currentYearIndex === years.length - 1 }"
                                    class="flex items-center justify-center w-10 h-10 {{ themeClass('button') }} bg-opacity-70 rounded-lg hover:bg-opacity-100 transition-all transform">
                                    <span x-show="isLoading" class="absolute inset-0 flex items-center justify-center">
                                        <i class="fas fa-spinner fa-spin"></i>
                                    </span>
                                    <i x-show="!isLoading" class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <button wire:click.prevent="startSync" class="relative group py-3 px-4 {{ themeClass('button-accent') }} rounded-xl hover:bg-amber-600 transition-colors">
                                <i class="fab fa-strava text-2xl" wire:loading.class="animate-spin" wire:target="startSync"></i>
                                <div wire:loading wire:target="startSync" class="absolute -bottom-12 right-0 bg-amber-500 bg-opacity-80 p-3 rounded shadow-lg text-sm whitespace-nowrap {{ themeClass('text-1') }}">
                                    Synchronizing...
                                </div>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 {{ themeClass('text-1') }} rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                    Synchronize with Strava
                                </div>
                            </button>
                            <button wire:click.prevent="deleteAll" class="hidden sm:block relative group py-3 px-4 {{ themeClass('button-danger') }} rounded-xl transition-colors">
                                <i class="fas fa-trash-alt text-2xl"></i>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 {{ themeClass('text-1') }} rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
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
                    <h2 class="text-xl font-bold text-gray-800 mb-2">
                        <div class="flex items-center">
                            <div class="flex gap-2 justify-between w-full">
                                <div class="flex items-center gap-2">
                                    <button wire:click.prevent="deleteMonth('{{ $monthKey }}')" class="hidden sm:block relative group text-red-500 hover:text-red-700 shrink-0">
                                        <i class="fas fa-trash-alt"></i>
                                        <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                            Delete training sessions for the month
                                        </div>
                                    </button>
                                    <span class="{{ themeClass('text-1') }} ms-2">{{ $monthName }}</span>
                                    @if($hasMismatch)
                                        <span class="text-xs text-red-500 font-normal">
                                            Corrected month name
                                        </span>
                                    @endif
                                </div>
                                <span class="{{ themeClass('text-1') }} font-normal text-base sm:text-lg sm:ml-2">
                                    <div class="hidden sm:flex gap-2">
                                        @foreach(['distance', 'elevation', 'time'] as $stat)
                                            <span class="inline-flex items-center gap-1 sm:gap-2 px-2 py-1">
                                                <i class="fas fa-{{ $statIcons[$stat] }} mr-1"></i>
                                                <span class="text-{{ $statColors[$stat] }}-500">
                                                    @if($stat === 'distance')
                                                        {{ number_format($monthStats[$monthKey]['actual'][$stat], 1) }}
                                                    @elseif($stat === 'time')
                                                        {{ formatTime((int)($monthStats[$monthKey]['actual'][$stat])) }}
                                                    @else
                                                        {{ $monthStats[$monthKey]['actual'][$stat] }}
                                                    @endif
                                                </span>
                                                @if($monthStats[$monthKey]['planned'][$stat] > 0)
                                                    <span class="text-gray-400 text-sm">/ 
                                                        @if($stat === 'time')
                                                            {{ formatTime($monthStats[$monthKey]['planned'][$stat]) }}
                                                        @else
                                                            {{ $stat === 'distance' ? number_format($monthStats[$monthKey]['planned'][$stat], 1) : $monthStats[$monthKey]['planned'][$stat] }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </span>                        
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
                        <div class="relative rounded-xl shadow-lg ps-2 mb-2 overflow-hidden">
                            <!-- Background overlay plus visible -->
                            <div class="absolute inset-0 opacity-30 bg-gradient-to-br from-{{ $lightShade }} via-{{ $midShade }} to-{{ $darkShade }}"></div>
                            
                            <!-- Bande colorée à gauche pour une identification plus marquée -->
                            <div class="absolute left-0 top-0 bottom-0 w-2 bg-{{ $midShade }}"></div>
                            
                            <!-- Contenu de la semaine -->
                            <div class="relative z-10 week-header px-3 pt-2 pb-1 sm:px-4 rounded-t-xl {{ $week->type ? 'pl-5' : '' }}">
                                <div class="flex flex-col gap-4">
                                    <!-- Week info and controls -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="flex justify-between sm:flex-col gap-2">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="hidden sm:block px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray">
                                                    Week {{ $week->week_number }}
                                                </span>                                        
                                                <span class="text-sm {{ themeClass('text-1') }}">
                                                    {{ $week->start }} - {{ $week->end }}
                                                </span>
                                            </div>
                                        
                                            <div class="flex items-center gap-2">
                                                <div class="relative">
                                                    <select wire:change="updateWeekType({{ $week->id }}, $event.target.value)" class="{{ themeClass('input') }} appearance-none block pl-8 pr-10 py-1.5 text-sm rounded-md border focus:outline-none focus:ring-0 focus:border-gray-300">
                                                        <option value="">None</option>
                                                        @foreach ($weekTypes as $type)
                                                            <option value="{{ $type->id }}" data-color="{{ $type->color }}" {{ $week->week_type_id == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 left-2 flex items-center">
                                                        <i class="fas fa-tag {{ themeClass('text-2') }}"></i>
                                                    </div>
                                                </div>
                                                <button wire:click.prevent="deleteWeek('{{ $week->id }}')" class="hidden sm:block relative group {{ themeClass('text-1') }} hover:{{ themeClass('text-2') }} ms-2">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 {{ themeClass('text-1') }} rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                                        Delete training sessions for the week
                                                    </div>                                        
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Week stats -->
                                        <div class="flex justify-between sm:gap-6 md:gap-8">
                                            @foreach(['distance', 'elevation', 'time'] as $stat)
                                                <div class="flex flex-col md:w-32 lg:w-40">
                                                    <p class="text-sm text-gray-200 mb-2 text-center">
                                                        <i class="fas fa-{{ $statIcons[$stat] }} mr-2"></i>{{ ucfirst($stat) }}
                                                    </p>
                                                    <p class="text-xl font-bold text-gray-100 mb-2 text-center">
                                                        @if($stat === 'distance')
                                                            {{ number_format($week->actual_stats[$stat], 1) }}
                                                        @elseif($stat === 'time')
                                                            {{ formatTime((int)($week->actual_stats[$stat])) }}
                                                        @else
                                                            {{ $week->actual_stats[$stat] }}
                                                        @endif
                                                        
                                                        @if($week->planned_stats[$stat] > 0)
                                                            <span class="text-sm text-gray-300">/ 
                                                                @if($stat === 'time')
                                                                    {{ formatTime($week->planned_stats[$stat]) }}
                                                                @else
                                                                    {{ $stat === 'distance' ? number_format($week->planned_stats[$stat], 1) : $week->planned_stats[$stat] }}
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </p>
                                                    @if($week->planned_stats[$stat] > 0)
                                                        @php 
                                                            $percentage = ($week->actual_stats[$stat] / $week->planned_stats[$stat]) * 100;
                                                            $percentage = min($percentage, 100);
                                                        @endphp
                                                        <div class="w-full h-1.5 bg-white rounded-full">
                                                            <div class="h-1.5 bg-{{ $statColors[$stat] }}-300 rounded-full" 
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
                                            wire:click.stop="$dispatch('openModal', { component: 'training-modal', arguments: { date: '{{ $dayDate->format('Y-m-d') }}' }})" 
                                            class="relative block p-2 rounded-lg border min-h-24 cursor-pointer
                                                    {{ $day['is_today'] ? 'border-2 border-opacity-100 ' . themeClass('border-accent') . ' bg-opacity-20 ' . themeClass('bg-accent') : 'border-opacity-30 border-white' }}">
                                            <!-- Day header -->
                                            <div class="absolute top-2 left-2">
                                                <div>
                                                    <span class="text-sm {{ themeClass('text-2') }}">{{ $day['name'] }}</span>
                                                    <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ $day['number'] }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Activities badges -->
                                            @php 
                                                $dayActivities = $activities->filter(function ($activity) use ($dayDate) {
                                                    return $activity->start_date->isSameDay($dayDate);
                                                });
                                            @endphp
                                            @if($dayActivities->isNotEmpty())
                                                <div class="absolute top-2 right-2 flex flex-wrap gap-2 sm:gap-1">
                                                    @foreach($dayActivities as $activity)
                                                    <div class="relative group">
                                                        <a wire:click.stop="$dispatch('openModal', { component: 'activity-modal', arguments: { id: '{{ $activity->id }}' }})" 
                                                            class="relative group cursor-pointer">
                                                                <div class="w-10 h-10 sm:w-8 sm:h-8 rounded-full flex items-center justify-center bg-orange-500 text-white text-base sm:text-sm hover:bg-orange-600 transition-colors">
                                                                    <i class="fas fa-running"></i>
                                                                </div>
                                                            </a>                                                      
                                                        <div class="z-50 absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                                            {{ $activity->name }}
                                                        </div>
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
                                                <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                                                    @foreach($dayTrainings as $training)
                                                        <a wire:click.stop="$dispatch('openModal', { component: 'training-modal', arguments: { id: '{{ $training->id }}' }})" class="relative group" 
                                                            draggable="true" 
                                                            ondragstart="onDragStart(event, {{ $training->id }})">
                                                            <div class="w-10 h-10 sm:w-8 sm:h-8 rounded-full flex items-center justify-center {{ $training->type->color }} text-white text-sm">
                                                                <i class="fas fa-{{ $training->type->icon }}"></i>
                                                            </div>                                                        
                                                            <div class="z-50 absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                                                {{ $training->type->name }}
                                                                @if($training->duration > 0)
                                                                    | {{ formatTime($training->duration * 60) }}
                                                                @endif
                                                                @if($training->distance > 0)
                                                                    | {{ formatDistance($training->distance) }}
                                                                @endif
                                                                @if($training->elevation > 0)
                                                                    | {{ $training->elevation }}m
                                                                @endif
                                                                @if($training->notes != '')
                                                                | {{ $training->notes }}
                                                            @endif
                                                            </div>
                                                        </a>                                                    
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
                    <div class="{{ themeClass('card') }} backdrop-blur-lg rounded-xl shadow-lg p-4">
                        <h3 class="font-bold {{ themeClass('text-1') }} mt-5 pb-4 mb-4 border-b {{ themeClass('divider') }}"><i class="fas fa-map-marker-alt mr-2 {{ themeClass('text-accent') }}"></i>
                            Navigation
                        </h3>
                        <nav class="">
                            @php
                                $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                            @endphp
                            <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-1 {{ themeClass('nav') }} rounded-lg transition-colors cursor-pointer">
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
                                class="flex items-center justify-between px-3 py-1 gap-2 rounded-xl {{ themeClass('nav') }} hover:{{ themeClass('nav-active') }} transition-all duration-200 group">
                                    <span>{{ $monthName }}</span>
                                    <span class="text-sm {{ themeClass('text-3') }} group-hover:{{ themeClass('text-2') }}">
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



