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
                <!-- Stats section -->
                <div class="flex flex-col lg:flex-row gap-4 sm:gap-8">
                    <!-- Stats wrapper -->
                    <div class="hidden sm:flex justify-between lg:justify-start gap-4">
                        @foreach(['distance', 'elevation', 'time'] as $stat)
                        <div class="flex flex-row items-start gap-3 w-full sm:w-auto">
                            <div class="py-3 px-4 bg-{{ $statColors[$stat] }}-100 rounded-xl">
                                <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-600 text-2xl"></i>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500 mb-1">{{ ucfirst($stat) }}</p>
                                <div class="flex flex-col items-center gap-2">
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
                    </div>

                    <!-- Controls wrapper -->
                    <div class="flex flex-row justify-between lg:justify-end lg:ml-auto gap-4 items-center">
                        <div class="relative mt-1" x-data="{ open: false, selectedYear: @entangle('year').defer }" x-init="selectedYear = @js($year)" @keydown.escape="open = false" @click.away="open = false">
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
                        <div class="flex gap-2">
                            <button wire:click.prevent="startSync" class="relative group py-3 px-4 bg-orange-100 rounded-xl text-orange-600 hover:bg-orange-200 transition-colors">
                                <i class="fas fa-sync text-orange-600 text-2xl" wire:loading.class="animate-spin" wire:target="startSync"></i>
                                <div wire:loading wire:target="startSync" class="absolute -bottom-12 right-0 bg-orange-100 p-3 rounded shadow-lg text-sm whitespace-nowrap">
                                    Synchronizing...
                                </div>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                    Synchronize with Strava
                                </div>
                            </button>
                            <button wire:click.prevent="deleteAll" class="hidden sm:block relative group py-3 px-4 bg-red-100 rounded-xl text-red-600 hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
                                <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
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
                    $monthDate = Carbon::createFromFormat('Y-m', $monthKey);
                    $monthName = $monthDate->format('F');
                @endphp
                <section id="{{ Str::slug($monthName) }}" class="mb-4 sm:mb-5">
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
                                    {{ $monthName }}
                                </div>
                                <span class="text-gray-500 font-normal text-base sm:text-lg sm:ml-2">
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
                        </div>
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
                            <div class="week-header p-3 sm:p-4 rounded-t-xl bg-gradient-to-{{ $direction }} from-{{ $color }} via-{{ $lighterColor }} to-{{ $color }} border-b">
                                <div class="flex flex-col gap-4">
                                    <!-- Week info and controls -->
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                        <div class="flex justify-between sm:flex-col gap-2">
                                            <div class="flex flex-wrap items-center gap-2">
                                                <span class="hidden sm:block px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray">
                                                    Week {{ $week->week_number }}
                                                </span>                                        
                                                <span class="text-sm text-gray-100">
                                                    {{ $week->start }} - {{ $week->end }}
                                                </span>
                                            </div>
                                        
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
                                                <button wire:click.prevent="deleteWeek('{{ $week->id }}')" class="hidden sm:block relative group text-gray-100 hover:text-gray-300 ms-2">
                                                    <i class="fas fa-trash-alt"></i>
                                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 w-max px-2 py-1 bg-gray-700 text-white rounded text-sm opacity-0 group-hover:opacity-100 transition-opacity z-50">
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
        <div x-data="{ mobileNavOpen: false }" class="xl:w-52 xl:sticky xl:top-4 xl:self-start">
            <button @click="mobileNavOpen = true" class="xl:hidden fixed top-0 right-0 z-50 w-12 h-12 bg-blue-500 rounded-full shadow-lg flex items-center justify-center text-white hover:bg-blue-600 ml-auto">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <div x-show="mobileNavOpen" @click.away="mobileNavOpen = false" class="xl:hidden fixed inset-0 bg-black/50 z-40" x-cloak></div>
            <div x-show="mobileNavOpen" class="xl:hidden fixed top-0 left-0 h-full w-64 bg-white shadow-xl z-50 transform transition-transform" :class="mobileNavOpen ? 'translate-x-0' : 'translate-x-full'" x-cloak>
                <div class="p-4 relative h-full overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Navigation</h3>
                        <button @click="mobileNavOpen = false" class="p-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <nav class="space-y-2">
                        @php
                            $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                        @endphp
                        <a onclick="document.getElementById('{{ $currentMonthSlug }}').scrollIntoView({ behavior: 'smooth' })" class="flex px-3 py-2 hover:bg-gray-50 transition-colors text-gray-700 hover:text-blue-600 cursor-pointer">
                            Current Month
                        </a>
                        <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-2 hover:bg-gray-50 transition-colors text-gray-700 hover:text-blue-600 cursor-pointer">
                            Scroll to top
                        </a>
                        @foreach ($months as $monthKey => $weeksInMonth)
                            @php
                                if(substr($monthKey, 0, 4) != $year) {
                                    continue;
                                }
                                $monthDate = Carbon::createFromFormat('Y-m', $monthKey);
                                $monthName = $monthDate->format('F');
                            @endphp
                            <a href="#{{ Str::slug($monthName) }}" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-700 hover:text-blue-600 group">
                                <span>{{ $monthName }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="bg-white rounded-xl shadow-lg p-4">
                    <h3 class="font-bold text-gray-800 mb-3 mt-5"><i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                        Navigation
                    </h3>
                    <nav class="">
                        @php
                            $currentMonthSlug = Str::slug(Carbon::now()->format('F'));
                        @endphp
                        <a onclick="document.getElementById('{{ $currentMonthSlug }}').scrollIntoView({ behavior: 'smooth' })" class="flex px-3 py-2 hover:bg-gray-50 transition-colors text-gray-700 hover:text-blue-600 cursor-pointer">
                            Current Month
                        </a>
                        <a onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" class="flex px-3 py-2 hover:bg-gray-50 transition-colors text-gray-700 hover:text-blue-600 cursor-pointer">
                            Scroll to top
                        </a>
                        @foreach ($months as $monthKey => $weeksInMonth)
                            @php
                                if(substr($monthKey, 0, 4) != $year) {
                                    continue;
                                }
                                $monthDate = Carbon::createFromFormat('Y-m', $monthKey);
                                $monthName = $monthDate->format('F');
                            @endphp
                            <a href="#{{ Str::slug($monthName) }}" class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors text-gray-700 hover:text-blue-600 group">
                                <span>{{ $monthName }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
    </div>    
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