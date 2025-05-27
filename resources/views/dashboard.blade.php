@extends('layouts.app')
@section('content')
<div class="dashboard-content-container">
    <div class="container mx-auto p-4 md:p-8">        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Next Workouts Card -->
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all duration-300 opacity-0" id="nextWorkoutCard">
                <h2 class="text-2xl font-bold mb-4 flex items-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('dashboard.next_objectives.title') }}
                </h2>
                
                <div class="mb-4" id="nextWorkoutContent">
                    @if(isset($nextWorkouts) && count($nextWorkouts) > 0)
                        @foreach($nextWorkouts->take(2) as $workout)
                            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-b border-white border-opacity-20' : '' }}">
                                <div class="text-2xl font-bold text-amber-300 mb-2">{{ $workout->title }}</div>
                                <div class="flex items-center mb-2 gap-2 text-white">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ \Carbon\Carbon::parse($workout->date)->translatedFormat('l, j F Y') }}</span>
                                </div>
                                @if($workout->duration > 0)
                                <div class="flex items-center mb-2 gap-2 text-cyan-200">
                                    <i class="fas fa-stopwatch"></i>
                                    <span>{{ formatTime($workout->duration * 60) }}</span>
                                </div>
                                @endif
                                @if(isset($workout->distance) && $workout->distance > 0)
                                <div class="flex items-center mb-2 gap-2 text-cyan-200">
                                    <i class="fas fa-route"></i>
                                    <span>{{ $workout->distance }} {{ __('dashboard.units.km') }}</span>
                                </div>
                                @endif
                                @if(isset($workout->elevation) && $workout->elevation > 0)
                                <div class="flex items-center mb-2 gap-2 text-cyan-200">
                                    <i class="fas fa-mountain"></i>
                                    <span>{{ $workout->elevation }} {{ __('dashboard.units.m') }}</span>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6">
                            <p class="text-xl text-cyan-200 mb-4">{{ __('dashboard.next_objectives.no_upcoming') }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Race Card -->
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all duration-300 opacity-0 flex flex-col" id="raceCard">
                <h2 class="text-2xl font-bold mb-4 flex items-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                    </svg>
                    {{ __('dashboard.next_race.title') }}
                </h2>
                
                <div class="flex-grow mb-4" id="nextRaceContent">
                    @if(isset($nextRace))
                        <div class="text-3xl font-bold text-amber-300 mb-2">{{ $nextRace->title }}</div>
                        <div class="flex items-center mb-2 gap-2 text-white">
                            <i class="fas fa-calendar"></i>
                            <span>{{ \Carbon\Carbon::parse($nextRace->date)->translatedFormat('l, j F Y') }}</span>
                        </div>
                        @if($nextRace->duration > 0)
                        <div class="flex items-center mb-2 gap-2 text-cyan-200">
                            <i class="fas fa-stopwatch"></i>
                            <span>{{ formatTime($nextRace->duration * 60) }}</span>
                        </div>
                        @endif
                        @if(isset($nextRace->distance) && $nextRace->distance > 0)
                        <div class="flex items-center mb-2 gap-2 text-cyan-200">
                            <i class="fas fa-route"></i>
                            <span>{{ $nextRace->distance }} {{ __('dashboard.units.km') }}</span>
                        </div>
                        @endif
                        @if(isset($nextRace->elevation) && $nextRace->elevation > 0)
                        <div class="flex items-center mb-2 gap-2 text-cyan-200">
                            <i class="fas fa-mountain"></i>
                            <span>{{ $nextRace->elevation }} {{ __('dashboard.units.m') }}</span>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <p class="text-xl text-cyan-200 mb-4">{{ __('dashboard.next_race.no_upcoming') }}</p>
                        </div>
                    @endif
                </div>
                
                @if(isset($nextRace))
                    <div class="mt-auto pt-4">
                        <h3 class="font-semibold mb-2 text-white">{{ __('dashboard.next_race.time_until') }}</h3>
                        <div class="grid grid-cols-4 gap-2 text-center" id="raceCountdown" data-target-date="{{ $nextRace->date }}">
                            <div class="bg-red-600 bg-opacity-60 rounded-lg p-2">
                                <span id="raceDays" class="text-3xl font-bold block text-white">--</span>
                                <span class="text-xs text-cyan-200">{{ __('dashboard.next_race.countdown.days') }}</span>
                            </div>
                            <div class="bg-red-600 bg-opacity-60 rounded-lg p-2">
                                <span id="raceHours" class="text-3xl font-bold block text-white">--</span>
                                <span class="text-xs text-cyan-200">{{ __('dashboard.next_race.countdown.hours') }}</span>
                            </div>
                            <div class="bg-red-600 bg-opacity-60 rounded-lg p-2">
                                <span id="raceMinutes" class="text-3xl font-bold block text-white">--</span>
                                <span class="text-xs text-cyan-200">{{ __('dashboard.next_race.countdown.mins') }}</span>
                            </div>
                            <div class="bg-red-600 bg-opacity-60 rounded-lg p-2">
                                <span id="raceSeconds" class="text-3xl font-bold block text-white">--</span>
                                <span class="text-xs text-cyan-200">{{ __('dashboard.next_race.countdown.secs') }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Statistics Card -->
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 shadow-lg transform hover:scale-105 transition-all duration-300 opacity-0 flex flex-col" id="statsCard">
                <h2 class="text-2xl font-bold mb-4 flex items-center text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    {{ __('dashboard.progress.title') }}
                </h2>
                
                <div class="space-y-6 flex-grow mb-4">
                    <!-- Weekly Distance -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-cyan-200">{{ __('dashboard.progress.weekly_distance') }}</span>
                            @if(isset($weeklyGoal->distance) && $weeklyGoal->distance > 0)
                                <span class="text-sm font-bold text-white">{{ number_format($weeklyStats->distance ?? 0, 1) }} / {{ number_format($weeklyGoal->distance, 1) }} {{ __('dashboard.units.km') }}</span>
                            @else
                                <span class="text-sm font-bold text-white">{{ number_format($weeklyStats->distance ?? 0, 1) }} {{ __('dashboard.units.km') }}</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-800 bg-opacity-50 rounded-full h-2.5">
                            @if(isset($weeklyGoal->distance) && $weeklyGoal->distance > 0)
                                <div class="bg-blue-400 h-2.5 rounded-full progress-bar" style="width: {{ isset($weeklyStats->distance) ? min(($weeklyStats->distance / $weeklyGoal->distance) * 100, 100) : 0 }}%"></div>
                            @else
                                <div class="bg-blue-400 h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Weekly Duration -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-cyan-200">{{ __('dashboard.progress.weekly_duration') }}</span>
                            @if(isset($weeklyGoal->duration) && $weeklyGoal->duration > 0)
                                <span class="text-sm font-bold text-white">{{ formatTime($weeklyStats->duration ?? 0) }} / {{ formatTime($weeklyGoal->duration) }}</span>
                            @else
                                <span class="text-sm font-bold text-white">{{ formatTime($weeklyStats->duration ?? 0) }}</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2.5">
                            @if(isset($weeklyGoal->duration) && $weeklyGoal->duration > 0)
                                <div class="bg-green-400 h-2.5 rounded-full progress-bar" style="width: {{ isset($weeklyStats->duration) ? min(($weeklyStats->duration / $weeklyGoal->duration) * 100, 100) : 0 }}%"></div>
                            @else
                                <div class="bg-green-400 h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Weekly Elevation -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-cyan-200">{{ __('dashboard.progress.weekly_elevation') }}</span>
                            @if(isset($weeklyGoal->elevation) && $weeklyGoal->elevation > 0)
                                <span class="text-sm font-bold text-white">{{ number_format($weeklyStats->elevation ?? 0) }} / {{ number_format($weeklyGoal->elevation) }} {{ __('dashboard.units.m') }}</span>
                            @else
                                <span class="text-sm font-bold text-white">{{ number_format($weeklyStats->elevation ?? 0) }} {{ __('dashboard.units.m') }}</span>
                            @endif
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2.5">
                            @if(isset($weeklyGoal->elevation) && $weeklyGoal->elevation > 0)
                                <div class="bg-red-400 h-2.5 rounded-full progress-bar" style="width: {{ isset($weeklyStats->elevation) ? min(($weeklyStats->elevation / $weeklyGoal->elevation) * 100, 100) : 0 }}%"></div>
                            @else
                                <div class="bg-red-400 h-2.5 rounded-full progress-bar" style="width: 0%"></div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Monthly Consistency -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-cyan-200">{{ __('dashboard.progress.monthly_consistency') }}</span>
                            <span class="text-sm font-bold text-white">{{ $monthlyConsistency ?? 0 }}{{ __('dashboard.units.percent') }}</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2.5">
                            <div class="bg-purple-400 h-2.5 rounded-full progress-bar" style="width: {{ $monthlyConsistency ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Calendar Link Button -->
                @php
                    // Get current month number for the anchor
                    $currentMonthNumber = \Carbon\Carbon::now()->format('m');
                    
                    // Get current month in user's locale for display
                    \Carbon\Carbon::setLocale(app()->getLocale());
                    $currentMonthName = \Carbon\Carbon::now()->translatedFormat('F');
                @endphp
                <div class="mt-auto pt-4 border-t border-white border-opacity-20">
                    <a href="{{ route('calendar') }}#{{ $currentMonthNumber }}" class="flex items-center justify-center gap-2 px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-colors w-full">
                        <i class="fas fa-calendar"></i>
                        <span>{{ __('dashboard.progress.view_calendar', ['month' => $currentMonthName]) }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div> 
</div>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    
    .dashboard-content-container {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .dashboard-content-container::-webkit-scrollbar {
        width: 0;
        display: none;
    }
    
    /* .modal-content {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .modal-content::-webkit-scrollbar {
        width: 0;
        display: none;
    } */
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {        
        // Animations on page load            
        setTimeout(() => {
            document.getElementById('nextWorkoutCard').classList.remove('opacity-0');
            
            setTimeout(() => {
                document.getElementById('raceCard').classList.remove('opacity-0');
                
                setTimeout(() => {
                    document.getElementById('statsCard').classList.remove('opacity-0');
                    
                    // Animate progress bars
                    const progressBars = document.querySelectorAll('.progress-bar');
                    progressBars.forEach(bar => {
                        const width = bar.style.width;
                        bar.style.width = '0%';
                        setTimeout(() => {
                            bar.style.transition = 'width 1s ease-out';
                            bar.style.width = width;
                        }, 100);
                    });
                }, 200);
            }, 200);
        }, 200);
        
        // Race Countdown timer functionality
        const raceCountdownElement = document.getElementById('raceCountdown');
        if (raceCountdownElement) {
            const targetDate = new Date(raceCountdownElement.getAttribute('data-target-date')).getTime();
            
            // Update countdown every second
            const raceCountdownTimer = setInterval(function() {
                // Get current date and time
                const now = new Date().getTime();
                
                // Find the time difference between now and the target date
                const distance = targetDate - now;
                
                // If the countdown is over, clear the interval
                if (distance < 0) {
                    clearInterval(raceCountdownTimer);
                    document.getElementById('raceDays').innerHTML = "0";
                    document.getElementById('raceHours').innerHTML = "0";
                    document.getElementById('raceMinutes').innerHTML = "0";
                    document.getElementById('raceSeconds').innerHTML = "0";
                    return;
                }
                
                // Time calculations for days, hours, minutes and seconds
                document.getElementById('raceDays').innerHTML = Math.floor(distance / (1000 * 60 * 60 * 24));
                document.getElementById('raceHours').innerHTML = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                document.getElementById('raceMinutes').innerHTML = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                document.getElementById('raceSeconds').innerHTML = Math.floor((distance % (1000 * 60)) / 1000);
            }, 1000);
        }
    });
</script>

<script>
    // Vérifier si un rafraîchissement du dashboard est nécessaire après sync
    document.addEventListener('DOMContentLoaded', function() {
        if (localStorage.getItem('dashboard_needs_refresh') === 'true') {
            localStorage.removeItem('dashboard_needs_refresh');
            // Petit délai pour éviter les problèmes de timing
            setTimeout(function() {
                window.location.reload();
            }, 500);
        }
    });
</script>
@endsection