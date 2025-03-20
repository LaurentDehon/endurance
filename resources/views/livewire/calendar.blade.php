<?php
    use App\http\Helpers\Helpers; 
    use Carbon\Carbon;
?>    

<div class="mx-auto p-2 sm:p-4">
    <div class="flex flex-col xl:flex-row gap-4 lg:gap-8">
        <!-- Main content -->
        <div class="flex-1">
            <!-- Global stats -->
            <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-8">
                <div class="flex flex-wrap gap-4 sm:gap-8 items-center">
                    @foreach(['distance', 'elevation', 'time'] as $stat)
                    <div class="flex flex-row items-start gap-3 w-full sm:w-auto">
                        <div class="py-3 px-4 bg-{{ $statColors[$stat] }}-100 rounded-xl">
                            <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-600 text-2xl"></i>
                        </div>
                        <div class="text-center md:text-left">
                            <p class="text-sm text-gray-500 mb-1">{{ ucfirst($stat) }}</p>
                            <div class="flex flex-col md:flex-row items-center md:items-baseline gap-2">
                                <p class="text-2xl font-bold text-gray-800">
                                    @if($stat === 'distance')
                                        {{ number_format($yearStats['actual'][$stat], 0, ',', '') }}
                                    @elseif($stat === 'time')
                                        {{ formatTime((int)($yearStats['actual'][$stat])) }}
                                    @else
                                        {{ number_format($yearStats['actual'][$stat], 0, ',', '') }}
                                    @endif
                                    
                                    @if($yearStats['planned'][$stat] > 0)
                                        <span class="text-sm text-gray-500">/ 
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
                    <div class="flex flex-wrap gap-2 w-full xl:w-auto xl:ml-auto items-center justify-end">
                        <div class="relative" x-data="{ open: false, selectedYear: @entangle('year').defer }" x-init="selectedYear = @js($year)" @keydown.escape="open = false" @click.away="open = false">
                            <button @click="open = !open" type="button" class="flex items-center gap-2 py-3 px-4 bg-white border rounded-lg hover:bg-gray-50">
                                <span x-text="selectedYear" class="font-medium text-gray-700"></span>
                                <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                            </button>    
                            <div x-show="open" x-cloak 
                                class="absolute right-0 z-50 mt-2 min-w-[6rem] max-h-60 overflow-y-auto bg-white border rounded-lg shadow-xl text-center" style="-ms-overflow-style: none; scrollbar-width: none;">                                
                                <div class="p-1">
                                    @foreach ($years as $y)
                                        <button type="button" @click="selectedYear = {{ $y }}; open = false" wire:click="setYear({{ $y }})" 
                                            class="flex w-full px-3 py-2 hover:bg-blue-50 text-lg rounded text-center justify-center {{ $y == $year ? 'bg-blue-100 font-semibold' : '' }}">
                                            {{ $y }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button wire:click.prevent="startSync" class="relative group py-3 px-4 bg-orange-100 rounded-xl text-orange-600 hover:bg-orange-200 transition-colors">
                            <i class="fas fa-sync text-orange-600 text-2xl" wire:loading.class="animate-spin" wire:target="startSync"></i>
                            <div wire:loading wire:target="startSync" class="absolute -bottom-12 right-0 bg-orange-100 p-3 rounded shadow-lg text-sm whitespace-nowrap">
                                Synchronizing...
                            </div>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                Synchronize with Strava
                            </div>
                        </button>
                        <button wire:click.prevent="deleteAll" class="relative group py-3 px-4 bg-red-100 rounded-xl text-red-600 hover:bg-red-200 transition-colors">
                            <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                Delete training sessions for the year
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Months -->
            @foreach ($months as $monthKey => $weeksInMonth)
                @php
                    $monthDate = Carbon::createFromFormat('Y-m', $monthKey);
                    $monthName = $monthDate->format('F Y');
                @endphp
                <section id="{{ Str::slug($monthName) }}" class="mb-4 sm:mb-5">
                    <!-- Month header -->
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                            {{ $monthName }}
                            <span class="text-gray-500 font-normal text-base sm:text-lg sm:ml-2">
                                <div class="flex flex-wrap gap-2 sm:gap-4">
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
                                                <span class="text-gray-400">/ 
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
                    
                        <button wire:click.prevent="deleteMonth('{{ $monthKey }}')" class="relative group mx-4 text-red-500 hover:text-red-700">
                            <i class="fas fa-trash-alt"></i>
                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                Delete training sessions for the month
                            </div>
                        </button>
                        
                    </h2>                 

                    <!-- Weeks -->
                    @foreach ($weeksInMonth as $week)
                        <div class="bg-white rounded-xl shadow-lg mb-2">
                            <!-- Week header -->
                            @php
                                $baseColor = $week->type->color ?? 'bg-slate-500';
                                $color = str_replace('bg-', '', $baseColor);
                                $lighterColor = preg_replace_callback('/-(\d{3})$/', function ($matches) {
                                    return '-' . max(50, $matches[1] - 150);
                                }, $color);
                                $direction = 'bl';
                            @endphp
                            <div class="week-header px-2 sm:px-3 py-2 rounded-t-xl bg-gradient-to-{{ $direction }} from-{{ $color }} via-{{ $lighterColor }} to-{{ $color }} border-b">
                                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray">
                                                Week {{ $week->week_number }}
                                            </span>                                        
                                            <span class="text-sm text-gray-100">
                                                {{ $week->start }} - {{ $week->end }}
                                            </span>
                                        </div>
                                    
                                        <div class="h-9">
                                            <div class="flex items-center gap-2">
                                                <div class="relative">
                                                    <select wire:change="updateWeekType({{ $week->id }}, $event.target.value)" class="bg-gray-100 appearance-none block pl-8 pr-10 py-1.5 text-sm rounded-md border focus:outline-none focus:ring-0 focus:border-gray-300">
                                                        <option value="">None</option>
                                                        @foreach ($weekTypes as $type)
                                                            <option value="{{ $type->id }}" data-color="{{ $type->color }}" {{ $week->week_type_id == $type->id ? 'selected' : '' }}>
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 left-2 flex items-center">
                                                        <i class="fas fa-tag text-gray-400"></i>
                                                    </div>
                                                </div>
                                                <button wire:click.prevent="deleteWeek('{{ $week->id }}')" class="relative group mx-2 text-gray-100 hover:text-gray-300">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
                                                        Delete training sessions for the week
                                                    </div>                                        
                                                </button>
                                            </div>
                                        </div>
                                    </div>                                                      

                                    <!-- Week stats -->
                                    <div class="flex flex-wrap gap-3 sm:gap-6 sm:ml-auto">
                                        @foreach(['distance', 'elevation', 'time'] as $stat)
                                            <div class="text-center min-w-[120px] sm:min-w-[160px]">
                                                <p class="text-s text-gray-300 mb-1">
                                                    <i class="fas fa-{{ $statIcons[$stat] }} mr-1"></i>{{ ucfirst($stat) }}
                                                </p>
                                                <p class="text-xl font-bold text-gray-100 mb-1">
                                                    @if($stat === 'distance')
                                                        {{ number_format($week->actual_stats[$stat], 1) }}
                                                    @elseif($stat === 'time')
                                                        {{ formatTime((int)($week->actual_stats[$stat])) }}
                                                    @else
                                                        {{ $week->actual_stats[$stat] }}
                                                    @endif
                                                    
                                                    @if($week->planned_stats[$stat] > 0)
                                                        <span class="text-sm">/ 
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
                                                    <div class="w-full h-1.5 bg-gray-200 rounded-full">
                                                        <div class="h-1.5 bg-{{ $statColors[$stat] }}-300 rounded-full" 
                                                            style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <!-- Days grid -->
                            <div class="p-2">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                                    @foreach ($week->days as $day)
                                        @php
                                            $dayDate = $day['date'];
                                        @endphp
                                        <div wire:key="day-{{ $dayDate->format('Y-m-d') }}"
                                            ondragover="onDragOver(event)" 
                                            ondrop="onDrop(event, '{{ $dayDate->format('Y-m-d') }}')" 
                                            ondragleave="onDragLeave(event)" 
                                            wire:click="$dispatch('openModal', { component: 'training-modal', arguments: { date: '{{ $dayDate->format('Y-m-d') }}' }})" class="relative block p-2 rounded-lg border min-h-24 cursor-pointer
                                                    {{ $day['is_today'] ? 'border-2 border-blue-300 bg-blue-50' : 'hover:border-blue-200' }}">
                                            <!-- Day header -->
                                            <div class="absolute top-2 left-2">
                                                <div>
                                                    <span class="text-sm text-gray-500">{{ $day['name'] }}</span>
                                                    <span class="text-sm font-bold text-gray-700">{{ $day['number'] }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Activities badges -->
                                            @php 
                                            $dayActivities = $activities->filter(function ($activity) use ($dayDate) {
                                                return $activity->start_date->isSameDay($dayDate);
                                            });
                                            @endphp
                                            @if($dayActivities->isNotEmpty())
                                                <div class="absolute top-2 right-2 flex flex-wrap gap-1">
                                                    @foreach($dayActivities as $activity)
                                                    <div class="relative group">
                                                        <a wire:click.stop="$dispatch('openModal', { component: 'activity-modal', arguments: { id: '{{ $activity->id }}' }})" 
                                                            class="relative group cursor-pointer">
                                                             <div class="w-8 h-8 rounded-full flex items-center justify-center bg-orange-500 text-white text-sm hover:bg-orange-600 transition-colors">
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
                                                <div class="absolute bottom-2 right-2 flex flex-wrap gap-1">
                                                    @foreach($dayTrainings as $training)
                                                        <a wire:click.stop="$dispatch('openModal', { component: 'training-modal', arguments: { id: '{{ $training->id }}' }})" class="relative group" 
                                                            draggable="true" 
                                                            ondragstart="onDragStart(event, {{ $training->id }})">
                                                            <div class="{{ $training->type->name === 'Race' ? 'h-10 w-10' : 'h-8 w-8' }} rounded-full flex items-center justify-center {{ $training->type->color }} text-white text-sm">
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
        <div class="xl:w-64">
            <div class="bg-white rounded-xl shadow-lg p-4 xl:sticky xl:top-4">
                <h3 class="font-bold text-gray-800 mb-3 mt-5"><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                    Navigation
                </h3>
                <nav class="space-y-2">
                    @foreach ($months as $monthKey => $weeksInMonth)
                        @php
                            if(substr($monthKey, 0, 4) != $year) {
                                continue;
                            }
                            $monthDate = Carbon::createFromFormat('Y-m', $monthKey);
                            $monthName = $monthDate->format('F Y');
                        @endphp
                        <a href="#{{ Str::slug($monthName) }}" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-700 hover:text-blue-600 group">
                            <span>{{ $monthName }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="flex flex-col gap-2 mt-2">
                    @php
                        $currentMonthSlug = Str::slug(Carbon::now()->format('F Y'));
                    @endphp
                    <button onclick="document.getElementById('{{ $currentMonthSlug }}').scrollIntoView({ behavior: 'smooth' })" 
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-calendar-day mr-2"></i>
                        Current Month
                    </button>
                    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" 
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors inline-flex items-center justify-center">
                        <i class="fas fa-arrow-up mr-2"></i>
                        Scroll to top
                    </button>
                </div>
            </div>
        </div>
    </div>    
</div>
@script
<script>
    Livewire.on('update-url', ({ year }) => {
        // Mettre à jour l'URL sans recharger la page
        const url = new URL(window.location);
        url.pathname = `/calendar/${year}`;
        window.history.pushState(null, '', url);
    });
</script>
@endscript