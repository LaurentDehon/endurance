@extends('layouts.app')

@section('content')

    <? use App\http\Helpers\Helpers; ?>
    
    <div class="container mx-auto p-4">
        <!-- Header -->
        <div class="flex flex-wrap justify-center items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('calendar.yearly', ['year' => $year - 1]) }}"
                    class="btn btn-primary px-6 py-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white transition-colors flex items-center">
                    <i class="fas fa-chevron-left mr-2"></i>{{ $year - 1 }}
                </a>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 text-center flex-grow">{{ $year }} Calendar</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('calendar.yearly', ['year' => $year + 1]) }}"
                    class="btn btn-primary px-6 py-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white transition-colors flex items-center">
                    {{ $year + 1 }}<i class="fas fa-chevron-right ml-2"></i>
                </a>
            </div>
        </div>


        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main content -->
            <div class="flex-1">
                <!-- Global stats -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex flex-wrap gap-8">
                        <div class="flex items-center gap-4">
                            <div class="py-3 px-4 bg-blue-100 rounded-xl">
                                <i class="fas fa-route text-blue-600 text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Distance</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $yearStats['distance'] }} km</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="py-3 px-4 bg-red-100 rounded-xl">
                                <i class="fas fa-mountain text-red-600 text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Elevation</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $yearStats['elevation'] }} m</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="py-3 px-4 bg-green-100 rounded-xl">
                                <i class="fas fa-stopwatch text-green-600 text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Time</p>
                                <p class="text-2xl font-bold text-gray-800">{{ formatTime($yearStats['time']) }}</p>
                            </div>
                        </div>
                        <!-- Add routine -->
                        <div class="tooltip flex items-center cursor-pointer ml-auto" onclick="handleClick(event, '{{ route('trainings.create-routine') }}')" data-tooltip="Add Routine">
                            <div class="py-3 px-4 bg-purple-100 rounded-xl">
                                <i class="fas fa-plus text-purple-600 text-2xl"></i>
                            </div>
                        </div>
                        <!-- Connect to Strava -->
                        <form method="GET" action="{{ route('strava.login') }}" class="flex items-center">
                            @csrf
                            <button type="submit" class="tooltip flex items-center cursor-pointer py-3 px-4 bg-orange-100 rounded-xl text-orange-600 hover:bg-orange-200 transition-colors" data-tooltip="Connect to Strava">
                                <i class="fas fa-sync text-orange-600 text-2xl"></i>
                            </button>
                        </form>
                        <!-- Delete all trainings -->
                        <form method="POST" action="{{ route('trainings.destroy-all') }}" class="flex items-center">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="tooltip flex items-center cursor-pointer py-3 px-4 bg-red-100 rounded-xl text-red-600 hover:bg-red-200 transition-colors" data-tooltip="Delete All">
                                <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Months -->
                @foreach ($months as $monthKey => $weeksInMonth)
                    @php
                        $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthKey);
                        $monthName = $monthDate->format('F Y');
                    @endphp
                    <section id="{{ Str::slug($monthName) }}" class="mb-5">
                        <!-- Month header -->
                        <div class="sticky top-0 bg-white/80 backdrop-blur-sm py-4 z-10 border-b">
                            <h2 class="text-xl font-bold text-gray-800">
                                {{ $monthName }}
                                <span class="text-gray-500 font-normal text-lg ml-2">
                                    <i class="fas fa-route mr-1"></i>{{ $monthStats[$monthKey]['distance'] }} km
                                    <i class="fas fa-mountain ml-3 mr-1"></i>{{ $monthStats[$monthKey]['elevation'] }} m
                                    <i class="fas fa-stopwatch ml-3 mr-1"></i>{{ formatTime($monthStats[$monthKey]['time']) }}
                                </span>
                            </h2>
                        </div>

                        <!-- Weeks -->
                        @foreach ($weeksInMonth as $week)
                            <div class="bg-white rounded-xl shadow-lg mb-2 overflow-hidden">
                                <!-- Week header -->
                                <div class="week-header px-3 py-2 bg-{{ $week->type->color ?? 'gray-500' }} border-b flex items-center gap-4 flex-wrap">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray">
                                                Week {{ $week->week_number }}
                                            </span>
                                            <span class="text-sm text-gray-300">
                                                {{ $week->start }} - {{ $week->end }}
                                            </span>
                                        </div>
                                    
                                        <form method="POST" action="{{ route('calendar.update-week-type', $week->id) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="flex items-center gap-2">
                                                <div class="relative">
                                                    <select name="week_type_id" onchange="updateWeekType(this)"
                                                        class="bg-gray-100 appearance-none block pl-8 pr-10 py-1.5 text-sm rounded-md border focus:outline-none">
                                                        <option value="">None</option>
                                                        @foreach ($weekTypes as $type)
                                                            <option value="{{ $type->id }}"
                                                                {{ $week->week_type_id == $type->id ? 'selected' : '' }}
                                                                data-color="{{ $type->color }}">
                                                                {{ $type->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 left-2 flex items-center">
                                                        <i class="fas fa-tag text-gray-400"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>                                                                   

                                    <!-- Week stats -->
                                    <div class="flex gap-6 ml-auto">
                                        <div class="text-center">
                                            <p class="text-s text-gray-300 mb-1"><i class="fas fa-route mr-1"></i>Distance</p>
                                            <p class="text-xl font-bold text-gray-100">{{ $week->training_stats['distance'] }} km</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-s text-gray-300 mb-1"><i class="fas fa-mountain mr-1"></i>Elevation</p>
                                            <p class="text-xl font-bold text-gray-100">{{ $week->training_stats['elevation'] }} m</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-s text-gray-300 mb-1"><i class="fas fa-stopwatch mr-1"></i>Time</p>
                                            <p class="text-xl font-bold text-gray-100">{{ formatTime($week->training_stats['time']) }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Days grid -->
                                <div class="p-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                                        @foreach ($week->days as $day)
                                            <div onclick="handleClick(event, '{{ route('trainings.create', ['date' => $day['date']->toDateString()]) }}')"
                                            class="relative block p-2 rounded-lg border min-h-24
                                                        {{ $day['is_today'] ? 'border-2 border-blue-300 bg-blue-50' : 'hover:border-blue-200' }}
                                                        {{ count($day['trainings']) == 0 ? 'bg-gray-200' : '' }}" style="cursor: pointer">
                                                <!-- Day header -->
                                                <div class="flex justify-between items-center mb-2">
                                                    <div>
                                                        <span class="text-sm text-gray-500">{{ $day['name'] }}</span>
                                                        <span class="text-sm font-bold text-gray-700">{{ $day['number'] }}</span>
                                                    </div>
                                                </div>

                                                <!-- Training badges -->
                                                @if(count($day['trainings']) > 0)
                                                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                                                        @foreach($day['trainings'] as $training)                                                        
                                                            <a class="tooltip" onclick="handleClick(event, '{{ route('trainings.show', $training['id']) }}')"
                                                            data-tooltip="{{ $training['type_name'] }}">
                                                                <div class="w-6 h-6 rounded-full flex items-center justify-center 
                                                                            {{ $training['type_color'] }} text-white text-sm">
                                                                    <i class="fas fa-{{ $training['type_icon'] }}"></i>
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
            <div class="lg:w-45">
                <div class="bg-white rounded-xl shadow-lg p-4 sticky top-4">
                    <h3 class="font-bold text-gray-800 mb-3 mt-5"><i
                            class="fas fa-map-marker-alt mr-2 text-blue-500"></i>Navigation</h3>
                    <nav class="space-y-2">
                        @foreach ($months as $monthKey => $weeksInMonth)
                            @php
                                $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $monthKey);
                                $monthName = $monthDate->format('F Y');
                            @endphp
                            <a href="#{{ Str::slug($monthName) }}"
                                class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors
                              text-gray-700 hover:text-blue-600 group">
                                <span>{{ $monthName }}</span>
                            </a>
                        @endforeach
                    </nav>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>

// Restoring scroll position
document.addEventListener('DOMContentLoaded', function() {
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    const savedScrollY = sessionStorage.getItem('scrollY');

    if (savedScrollY !== null) {
        window.scrollTo({ top: parseInt(savedScrollY), left: 0, behavior: 'instant' });
        setTimeout(() => {
            sessionStorage.removeItem('scrollY');
        }, 50);
    }
});

// Week type update
function updateWeekType(select) {
    const form = select.closest('form');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(data => {
        if (data.success) {
            const header = form.closest('.week-header');              
            if (header && data.week_type_color)
                header.className = header.className.replace(/bg-\S+/g, `bg-${data.week_type_color}`);                
            else header.className = header.className.replace(/bg-\S+/g, `bg-gray-500`);

            showToast('Week type updated successfully', 'success', 3000);
        } else showToast('Failed to update week type', 'error', 5000);
    })
    .catch(error => {
        console.error('Erreur:', error);
        showToast('Failed to update week type', 'error', 5000);
    });
}    

// Training modal
function handleClick(event, url) {
    event.preventDefault();
    event.stopPropagation();
    
    scrollPosition = window.scrollY;
    sessionStorage.setItem('scrollY', window.scrollY);
    
    openModal(url);
}

// Modal opening
function openModal(url) {
    const modal = document.getElementById('training-modal');
    modal.style.display = 'block';
    
    fetch(url + '?modal=1')
        .then(response => response.text())
        .then(html => {
            document.getElementById('modal-body').innerHTML = html;
            requestAnimationFrame(() => {
                window.scrollTo({ top: scrollPosition, left: 0, behavior: 'instant' });
            });
        });
}

// Modal closing
function closeModal() {
    const modal = document.getElementById('training-modal');
    modal.style.display = 'none';
    
    requestAnimationFrame(() => {
        window.scrollTo({ top: scrollPosition, left: 0, behavior: 'instant' });
    });
}

</script>