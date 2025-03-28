@extends('layouts.app')

@section('content')

    <?php
    use App\http\Helpers\Helpers; 
    use Carbon\Carbon;
    ?>    

    <div class="container mx-auto p-4">
        <!-- Header -->
        <div class="flex flex-wrap justify-center items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('calendar.index', ['year' => $year - 1]) }}"
                    class="btn btn-primary px-6 py-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white transition-colors flex items-center">
                    <i class="fas fa-chevron-left mr-5"></i>{{ $year - 1 }}
                </a>
            </div>
            <h1 class="text-4xl font-bold text-gray-800 text-center flex-grow">{{ $year }} Calendar</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('calendar.index', ['year' => $year + 1]) }}"
                    class="btn btn-primary px-6 py-3 rounded-full bg-blue-500 hover:bg-blue-600 text-white transition-colors flex items-center">
                    {{ $year + 1 }}<i class="fas fa-chevron-right ml-5"></i>
                </a>
            </div>
        </div>


        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Main content -->
            <div class="flex-1">
                <!-- Global stats -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                    <div class="flex flex-wrap gap-8" >
                        @foreach(['distance', 'elevation', 'time'] as $stat)
                            <div class="flex items-center gap-4">
                                <div class="py-3 px-4 bg-{{ $statColors[$stat] }}-100 rounded-xl">
                                    <i class="fas fa-{{ $statIcons[$stat] }} text-{{ $statColors[$stat] }}-600 text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">{{ ucfirst($stat) }}</p>
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-2xl font-bold text-gray-800">
                                            @if($stat === 'distance')
                                                {{ number_format($yearStats['actual'][$stat], 1) }}
                                            @elseif($stat === 'time')
                                                {{ formatTime((int)($yearStats['actual'][$stat])) }}
                                            @else
                                                {{ $yearStats['actual'][$stat] }}
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
                                    {{-- @if($yearStats['planned'][$stat] > 0)
                                        @php 
                                            $percentage = ($yearStats['actual'][$stat] / $yearStats['planned'][$stat]) * 100;
                                            $percentage = min($percentage, 100);
                                        @endphp
                                        <div class="w-24 h-1 bg-gray-200 rounded-full mt-1">
                                            <div class="h-1 bg-{{ $statColors[$stat] }}-500 rounded-full" 
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                        @endforeach
                        <div class="flex ml-auto gap-2 items-center">
                            <!-- Add routine -->
                            <a href="{{ route('trainings.create-routine') }}" class="tooltip cursor-pointer py-3 px-4 bg-purple-100 rounded-xl text-purple-600 hover:bg-purple-200 transition-colors" data-tooltip="Add Routine">
                                <i class="fas fa-plus text-purple-600 text-2xl"></i>
                            </a>                            
                            <!-- Connect to Strava -->
                            <a href="{{ route('strava.redirect') }}" class="tooltip cursor-pointer py-3 px-4 bg-orange-100 rounded-xl text-orange-600 hover:bg-orange-200 transition-colors" data-tooltip="Connect to Strava">
                                <i class="fas fa-link text-orange-600 text-2xl"></i></a>
                            <!-- Sync with Strava -->
                            <a href="#" onclick="sync(event)" class="tooltip cursor-pointer py-3 px-4 bg-orange-100 rounded-xl text-orange-600 hover:bg-orange-200 transition-colors" data-tooltip="Synchronize with Strava">
                                <i class="fas fa-sync text-orange-600 text-2xl"></i></a>
                        </div>                        
                    </div>
                </div>

                <!-- Months -->
                @foreach ($months as $monthKey => $weeksInMonth)
                    @php
                        $monthDate = Carbon::createFromFormat('Y-m', $monthKey);
                        $monthName = $monthDate->format('F Y');
                    @endphp
                    <section id="{{ Str::slug($monthName) }}" class="mb-5">
                        <!-- Month header -->
                        <h2 class="text-xl font-bold text-gray-800">
                            {{ $monthName }}
                            <span class="text-gray-500 font-normal text-lg ml-2">
                                @foreach(['distance', 'elevation', 'time'] as $stat)
                                    <span class="mr-4">
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
                                        {{-- @if($monthStats[$monthKey]['planned'][$stat] > 0)
                                            @php 
                                                $percentage = ($monthStats[$monthKey]['actual'][$stat] / $monthStats[$monthKey]['planned'][$stat]) * 100;
                                                $percentage = min($percentage, 100);
                                            @endphp
                                            <span class="inline-block w-12 h-1 bg-gray-200 rounded-full ml-1 align-middle">
                                                <span class="block h-1 bg-{{ $statColors[$stat] }}-500 rounded-full" 
                                                    style="width: {{ $percentage }}%"></span>
                                            </span>
                                        @endif --}}
                                    </span>
                                @endforeach
                            </span>
                        </h2>

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
                                    
                                        <form class="h-5" method="POST" action="{{ route('calendar.update-week-type', $week->id) }}">
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
                                        @foreach(['distance', 'elevation', 'time'] as $stat)
                                            <div class="text-center min-w-[90px]">
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
                                                    <div class="w-full h-1 bg-gray-200 rounded-full">
                                                        <div class="h-1 bg-{{ $statColors[$stat] }}-300 rounded-full" 
                                                            style="width: {{ $percentage }}%"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Days grid -->
                                <div class="p-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                                        @foreach ($week->days as $day)
                                            @php
                                                $dayDate = $day['date']->toDateString();
                                                $dayTrainings = $trainings->where('date', $day['date']);
                                                $dayActivities = $activities->where(function ($activity) use ($dayDate) {
                                                    return Carbon::parse($activity->start_date)->toDateString() === $dayDate;
                                                });
                                            @endphp
                                            <div onclick="handleClick(event, '{{ route('trainings.create', ['date' => $dayDate]) }}')"
                                            class="relative block p-2 rounded-lg border min-h-24" ondragover="onDragOver(event)" ondrop="onDrop(event, '{{ $dayDate }}')" ondragleave="onDragLeave(event)"
                                                        {{ $day['is_today'] ? 'border-2 border-blue-300 bg-blue-50' : 'hover:border-blue-200' }}
                                                        {{ $dayTrainings->count() == 0 ? 'bg-gray-200' : '' }}" style="cursor: pointer">
                                                <!-- Day header -->
                                                <div class="flex justify-between items-center mb-2">
                                                    <div>
                                                        <span class="text-sm text-gray-500">{{ $day['name'] }}</span>
                                                        <span class="text-sm font-bold text-gray-700">{{ $day['number'] }}</span>
                                                    </div>
                                                </div>

                                                <!-- Activities badges -->
                                                @if($dayActivities->isNotEmpty())
                                                    <div class="absolute top-2 right-2 flex flex-wrap gap-1">
                                                        @foreach($dayActivities as $activity)
                                                            <a class="tooltip" data-tooltip="{{ $activity->name }}">
                                                                <div class="w-6 h-6 rounded-full flex items-center justify-center 
                                                                        bg-orange-500 text-white text-sm">
                                                                    <i class="fas fa-running"></i>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- Training badges -->
                                                @if($dayTrainings->count() > 0)
                                                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                                                        @foreach($dayTrainings as $training)
                                                            <a class="tooltip" draggable="true" ondragstart="onDragStart(event, {{ $training->id }})" data-tooltip="{{ $training->type->name }}">
                                                                <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $training->type->color }} text-white text-sm"
                                                                    onclick="handleClick(event, '{{ route('trainings.show', $training->id) }}')">
                                                                    <i class="fas fa-{{ $training->type->icon }}"></i>
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
                <div id="sync-progress" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 max-w-xs mx-4">
                        <div class="flex items-center gap-4">
                            <div class="animate-spin text-orange-500">
                                <i class="fas fa-sync fa-2x"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 font-semibold">Synchronizing with Strava...</p>
                                <p class="text-sm text-gray-600">This may take a few moments.</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-orange-500 h-2 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side navigation -->
            <div class="lg:w-45">
                <div class="bg-white rounded-xl shadow-lg p-4 sticky top-4">
                    <h3 class="font-bold text-gray-800 mb-3 mt-5"><i
                            class="fas fa-map-marker-alt mr-2 text-blue-500"></i>Navigation</h3>
                    <nav class="space-y-2">
                        @foreach ($months as $monthKey => $weeksInMonth)
                            @php
                                $monthDate = Carbon::createFromFormat('Y-m', $monthKey);
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

<style>
#training-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.modal-content {
    position: relative;
    background: white;
    margin: 2rem auto;
    padding: 20px;
    max-width: 500px;
    border-radius: 8px;
}    
</style>

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
    const body = document.body;
    
    // Sauvegarde la position actuelle de manière non intrusive
    const scrollY = window.scrollY || window.pageYOffset;
    body.dataset.scrollY = scrollY;
    
    // Désactive le défilement avec CSS uniquement
    body.classList.add('overflow-hidden');
    
    // Ouvre la modal
    modal.style.display = 'block';
    
    fetch(url + '?modal=1')
        .then(response => response.text())
        .then(html => {
            document.getElementById('modal-body').innerHTML = html;
        });
}

// close Modal
function closeModal() {
    const modal = document.getElementById('training-modal');
    const body = document.body;
    const scrollY = parseInt(body.dataset.scrollY) || 0;
    
    // Réactive le défilement
    body.classList.remove('overflow-hidden');
    
    // Restaure la position sans animation
    requestAnimationFrame(() => {
        window.scrollTo({
            top: scrollY,
            behavior: 'instant'
        });
    });
    
    modal.style.display = 'none';
}

// Progress bar
function sync(event) {
    event.preventDefault();
    showProgressBar();

    fetch('/strava/sync', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
    })
    .then(response => response.json())    
    .then(data => {
        hideProgressBar();
        if (data.success) {
            showToast(data.message, 'success', 3000);
            window.location.reload();
        } else {
            showToast(data.message, 'error', 5000);
        }
    })
    .catch(error => {
        hideProgressBar()
        showToast(error.message || 'An error occurred', 'error', 5000);
    });
}

function showProgressBar() {
    document.getElementById('sync-progress').classList.remove('hidden');
}

function hideProgressBar() {
    document.getElementById('sync-progress').classList.add('hidden');
}

// Drag and drop
let isDragging = false;
let scrollPosition = 0;

// Drag start handler for training badges
function onDragStart(event, trainingId) {
    event.dataTransfer.setData('trainingId', trainingId);
    event.dataTransfer.effectAllowed = "move";
    scrollPosition = window.scrollY;
}

// Drag over handler for day cells
function onDragOver(event) {
    event.preventDefault();
    event.dataTransfer.dropEffect = "move";
    event.currentTarget.classList.add('bg-blue-50', 'border-blue-300');
}

// Drag leave handler
function onDragLeave(event) {
    event.currentTarget.classList.remove('bg-blue-50', 'border-blue-300');
}

// Drop handler for day cells
async function onDrop(event, targetDate) {
    event.preventDefault();
    event.currentTarget.classList.remove('bg-blue-50', 'border-blue-300');

    try {
        const trainingId = event.dataTransfer.getData('trainingId');
        
        const response = await fetch(`/trainings/${trainingId}/update-date`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ date: targetDate })
        });

        const contentType = response.headers.get('content-type');
        let data;

        if (contentType && contentType.includes('application/json')) {
            data = await response.json();
        } else {
            throw new Error('Réponse non-JSON du serveur');
        }

        if (!response.ok || !data.success) {
            throw new Error(data.message || 'Erreur pendant la mise à jour');
        }

        // Rafraîchir seulement la partie nécessaire
        sessionStorage.setItem('scrollY', window.scrollY);
        window.location.reload();

    } catch (error) {
        console.error('Erreur:', error);
        showToast(error.message || 'Erreur pendant le déplacement', 'error', 5000);
    }
}

// Modify click handler to ignore drag events
function handleClick(event, url) {
    if (isDragging) {
        isDragging = false;
        return;
    }
    event.preventDefault();
    event.stopPropagation();
    
    sessionStorage.setItem('scrollY', window.scrollY);
    openModal(url);
}

// Reset dragging state on drag end
document.addEventListener('dragend', () => {
    isDragging = false;
});
</script>