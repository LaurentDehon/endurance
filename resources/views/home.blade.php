@extends('layouts.app')
@section('content')
<div class="home-content-container">
    <div class="container mx-auto px-4 py-12">
        <!-- Welcome Section -->
        <div class="welcome-section mb-10 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="welcomeSection">
            <div class="flex items-center gap-28">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-2 {{ themeClass('text-1') }}">Welcome, {{ Auth::user()->name }}</h1>
                    <p class="text-xl {{ themeClass('text-2') }}">Your journey continues today. Let's make it count.</p>
                </div>
                <button id="openWelcomeModal" class="{{ themeClass('button-accent') }} relative p-3 px-6 rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center justify-center group animate-pulse hover:animate-none">
                    <div class="absolute inset-0 rounded-full bg-yellow-300 opacity-30 animate-ping"></div>
                    <i class="fas fa-info-circle text-lg mr-2"></i>
                    <span class="font-bold">Message de Bienvenue</span>
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Next Trainings Card -->
            <div class="{{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 shadow-xl border transform hover:scale-105 transition-all duration-300 opacity-0" id="nextTrainingCard">
                <h2 class="text-2xl font-bold mb-4 flex items-center {{ themeClass('text-1') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Next Objectives
                </h2>
                
                <div class="mb-4" id="nextTrainingContent">
                    @if(isset($nextTrainings) && count($nextTrainings) > 0)
                        @foreach($nextTrainings->take(2) as $training)
                            <div class="mb-4 pb-4 {{ !$loop->last ? 'border-b border-white border-opacity-20' : '' }}">
                                <div class="text-2xl font-bold {{ themeClass('text-accent') }} mb-2">{{ $training->title }}</div>
                                <div class="flex items-center mb-2 gap-2 {{ themeClass('text-1') }}">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ \Carbon\Carbon::parse($training->date)->format('l, F j, Y') }}</span>
                                </div>
                                @if($training->duration > 0)
                                <div class="flex items-center mb-2 gap-2 {{ themeClass('text-2') }}">
                                    <i class="fas fa-stopwatch"></i>
                                    <span>{{ formatTime($training->duration * 60) }}</span>
                                </div>
                                @endif
                                @if(isset($training->distance) && $training->distance > 0)
                                <div class="flex items-center mb-2 gap-2 {{ themeClass('text-2') }}">
                                    <i class="fas fa-route"></i>
                                    <span>{{ $training->distance }} km</span>
                                </div>
                                @endif
                                @if(isset($training->elevation) && $training->elevation > 0)
                                <div class="flex items-center mb-2 gap-2 {{ themeClass('text-2') }}">
                                    <i class="fas fa-mountain"></i>
                                    <span>{{ $training->elevation }} m</span>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-6">
                            <p class="text-xl {{ themeClass('text-2') }} mb-4">No upcoming objectives scheduled</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Race Card -->
            <div class="{{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 shadow-xl border transform hover:scale-105 transition-all duration-300 opacity-0 flex flex-col" id="raceCard">
                <h2 class="text-2xl font-bold mb-4 flex items-center {{ themeClass('text-1') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                    </svg>
                    Next Race
                </h2>
                
                <div class="flex-grow mb-4" id="nextRaceContent">
                    @if(isset($nextRace))
                        <div class="text-3xl font-bold {{ themeClass('text-accent') }} mb-2">{{ $nextRace->title }}</div>
                        <div class="flex items-center mb-2 gap-2 {{ themeClass('text-1') }}">
                            <i class="fas fa-calendar"></i>
                            <span>{{ \Carbon\Carbon::parse($nextRace->date)->format('l, F j, Y') }}</span>
                        </div>
                        @if($nextRace->duration > 0)
                        <div class="flex items-center mb-2 gap-2 {{ themeClass('text-2') }}">
                            <i class="fas fa-stopwatch"></i>
                            <span>{{ formatTime($nextRace->duration * 60) }}</span>
                        </div>
                        @endif
                        @if(isset($nextRace->distance) && $nextRace->distance > 0)
                        <div class="flex items-center mb-2 gap-2 {{ themeClass('text-2') }}">
                            <i class="fas fa-route"></i>
                            <span>{{ $nextRace->distance }} km</span>
                        </div>
                        @endif
                        @if(isset($nextRace->elevation) && $nextRace->elevation > 0)
                        <div class="flex items-center mb-2 gap-2 {{ themeClass('text-2') }}">
                            <i class="fas fa-mountain"></i>
                            <span>{{ $nextRace->elevation }} m</span>
                        </div>
                        @endif
                    @else
                        <div class="text-center py-6">
                            <p class="text-xl {{ themeClass('text-2') }} mb-4">No upcoming races scheduled</p>
                        </div>
                    @endif
                </div>
                
                @if(isset($nextRace))
                    <div class="mt-auto pt-4 border-t {{ themeClass('border-divider', 'border-white border-opacity-20') }}">
                        <h3 class="font-semibold mb-2 {{ themeClass('text-1') }}">Time Until Race:</h3>
                        <div class="grid grid-cols-4 gap-2 text-center" id="raceCountdown" data-target-date="{{ $nextRace->date }}">
                            <div class="{{ themeClass('countdown-bg', 'bg-red-600 bg-opacity-60') }} rounded-lg p-2">
                                <span id="raceDays" class="text-3xl font-bold block {{ themeClass('text-1') }}">--</span>
                                <span class="text-xs {{ themeClass('text-2') }}">DAYS</span>
                            </div>
                            <div class="{{ themeClass('countdown-bg', 'bg-red-600 bg-opacity-60') }} rounded-lg p-2">
                                <span id="raceHours" class="text-3xl font-bold block {{ themeClass('text-1') }}">--</span>
                                <span class="text-xs {{ themeClass('text-2') }}">HRS</span>
                            </div>
                            <div class="{{ themeClass('countdown-bg', 'bg-red-600 bg-opacity-60') }} rounded-lg p-2">
                                <span id="raceMinutes" class="text-3xl font-bold block {{ themeClass('text-1') }}">--</span>
                                <span class="text-xs {{ themeClass('text-2') }}">MINS</span>
                            </div>
                            <div class="{{ themeClass('countdown-bg', 'bg-red-600 bg-opacity-60') }} rounded-lg p-2">
                                <span id="raceSeconds" class="text-3xl font-bold block {{ themeClass('text-1',) }}">--</span>
                                <span class="text-xs {{ themeClass('text-2') }}">SECS</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Statistics Card -->
            <div class="{{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 shadow-xl border transform hover:scale-105 transition-all duration-300 opacity-0 flex flex-col" id="statsCard">
                <h2 class="text-2xl font-bold mb-4 flex items-center {{ themeClass('text-1') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Your Progress
                </h2>
                
                <div class="space-y-6 flex-grow mb-4">
                    <!-- Weekly Distance -->
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium {{ themeClass('text-2') }}">Weekly Distance</span>
                            @if(isset($weeklyGoal->distance) && $weeklyGoal->distance > 0)
                                <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ number_format($weeklyStats->distance ?? 0, 1) }} / {{ number_format($weeklyGoal->distance, 1) }} km</span>
                            @else
                                <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ number_format($weeklyStats->distance ?? 0, 1) }} km</span>
                            @endif
                        </div>
                        <div class="w-full {{ themeClass('progress-bg') }} bg-opacity-50 rounded-full h-2.5">
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
                            <span class="text-sm font-medium {{ themeClass('text-2') }}">Weekly Duration</span>
                            @if(isset($weeklyGoal->duration) && $weeklyGoal->duration > 0)
                                <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ formatTime($weeklyStats->duration ?? 0) }} / {{ formatTime($weeklyGoal->duration) }}</span>
                            @else
                                <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ formatTime($weeklyStats->duration ?? 0) }}</span>
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
                            <span class="text-sm font-medium {{ themeClass('text-2') }}">Weekly Elevation</span>
                            @if(isset($weeklyGoal->elevation) && $weeklyGoal->elevation > 0)
                                <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ number_format($weeklyStats->elevation ?? 0) }} / {{ number_format($weeklyGoal->elevation) }} m</span>
                            @else
                                <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ number_format($weeklyStats->elevation ?? 0) }} m</span>
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
                            <span class="text-sm font-medium {{ themeClass('text-2') }}">Monthly Consistency</span>
                            <span class="text-sm font-bold {{ themeClass('text-1') }}">{{ $monthlyConsistency ?? 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-700 rounded-full h-2.5">
                            <div class="bg-purple-400 h-2.5 rounded-full progress-bar" style="width: {{ $monthlyConsistency ?? 0 }}%"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Calendar Link Button -->
                @php
                    $currentMonthName = \Carbon\Carbon::now()->format('F');
                    $currentMonthSlug = Str::slug($currentMonthName);
                @endphp
                <div class="mt-auto pt-4 border-t {{ themeClass('divider') }}">
                    <a href="{{ route('calendar') }}#{{ $currentMonthSlug }}" class="flex items-center justify-center gap-2 px-4 py-2 {{ themeClass('button-accent') }} rounded-lg transition-colors w-full">
                        <i class="fas fa-calendar"></i>
                        <span>View {{ $currentMonthName }} in Calendar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Welcome Modal -->
    <div id="welcomeModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 items-center justify-center z-50 hidden">
        <div class="bg-gradient-to-br {{ themeClass('background') }} rounded-lg shadow-xl p-8 max-w-5xl w-full mx-auto max-h-[90vh] overflow-y-auto modal-content">
            <h2 class="text-2xl text-center font-bold {{ themeClass('text-1') }} mb-10">Bienvenue sur Zone 2</h2>
            <p class="{{ themeClass('text-2') }} mb-4">
                Merci de participer à la phase de bêta-test de Zone 2.  
                Votre rôle est essentiel pour nous aider à améliorer l'expérience utilisateur grâce à vos retours et suggestions.
            </p>
            <p class="{{ themeClass('text-2') }} mb-4">
                Zone 2 est conçu comme un outil permettant de créer des plans d'entraînement personnalisés.<br>
                Beaucoup de coureurs utilisent des fichiers Excel pour planifier leurs entraînements.  
                Notre solution propose une alternative automatisée et interactive avec des fonctionnalités avancées.  
                Il ne s'agit donc pas seulement d'un générateur de plans, mais plutôt d'un journal d'entraînement intelligent.
            </p>
            <p class="{{ themeClass('text-2') }} mb-4">
                L'application repose sur une approche structurée en blocs, où chaque semaine a un rôle précis et s'intègre dans un cycle global.  
                Cette organisation permet de mieux gérer la charge d'entraînement, d'optimiser la progression et d'éviter le surentraînement.  
                Chaque bloc est conçu pour vous préparer progressivement à votre objectif, afin d'être prêt(e) le jour de votre course ou événement.
            </p>

            <div class="mb-4">
                <h3 class="font-semibold {{ themeClass('text-1') }} mb-2">Pourquoi une approche par blocs ?</h3>
                <p class="{{ themeClass('text-2') }} mb-4">
                    Un entraînement efficace ne se limite pas à une simple accumulation de séances. Il repose sur une périodisation structurée, où chaque semaine joue un rôle spécifique dans votre progression.  
                    Zone 2 s'inspire de cette méthode et vous permet de :
                </p>
                <ul class="list-disc pl-6 {{ themeClass('text-2') }} space-y-2">
                    <li>Planifier vos semaines d'entraînement en fonction d'un objectif précis (développement, maintien, récupération…).</li>
                    <li>Structurer votre charge d'entraînement sur plusieurs semaines pour optimiser la progression et éviter le surentraînement.</li>
                    <li>Adapter chaque séance à son rôle dans le cycle global, plutôt que de la voir comme un événement isolé.</li>
                </ul>
            </div>

            <div class="mb-4">
                <h3 class="font-semibold {{ themeClass('text-1') }} mb-2">Principales fonctionnalités :</h3>
                <ul class="list-disc pl-6 {{ themeClass('text-2') }} space-y-2">
                    <li>Calendrier annuel interactif pour visualiser et organiser vos semaines d'entraînement.</li>
                    <li>Définition des objectifs (distance, durée, dénivelé) pour chaque séance.</li>
                    <li>Synchronisation automatique avec Strava pour importer vos activités.</li>
                    <li>Tableaux de bord comparant vos performances réelles avec vos objectifs.</li>
                    <li>Possibilité de définir des types de semaines d'entraînement (récupération, développement, maintien, etc.).</li>
                </ul>
            </div>
            <p class="{{ themeClass('text-2') }} mb-4">
                <strong>Note importante :</strong> L'application est actuellement en version bêta en anglais.  
                Une traduction complète en français est prévue pour la version finale.  
                Certaines fonctionnalités sont encore en développement et des bugs peuvent subsister.
            </p>
            <div class="mb-4">
                <h3 class="font-semibold {{ themeClass('text-1') }} mb-2">Exemple d'utilisation :</h3>
                <ul class="list-disc pl-6 {{ themeClass('text-2') }} space-y-2">
                    <li>Je commence par créer un "entraînement" le 14 juin, qui représente mon objectif (ma course).</li>
                    <li>Pour m'y préparer, je définis mes semaines d'entraînement en remontant dans le temps :</li>
                    <ul class="list-disc pl-6 {{ themeClass('text-2') }} space-y-2">
                        <li>La semaine de la course est marquée comme "Compétition".</li>
                        <li>Les deux semaines précédentes sont des semaines de "Taper", où je réduis progressivement la charge.</li>
                        <li>Les quatre semaines précédentes sont des semaines de "Maintien", correspondant au pic d'entraînement.</li>
                        <li>Les semaines antérieures sont des semaines de "Développement" pour construire ma condition physique.</li>
                        <li>Une semaine sur trois ou quatre est une semaine "Allégée" pour éviter le surentraînement.</li>
                        <li>Pendant les semaines de développement, je veille à ne pas augmenter ma charge d'entraînement de plus de 10% par semaine.</li>
                    </ul>
                    <li>En fonction de mon objectif (marathon, trail, 10 km…), je répartis mes séances chaque semaine pour atteindre mes objectifs hebdomadaires.</li>
                    <li>Personnellement, je préfère fixer mes objectifs hebdomadaires en temps plutôt qu'en distance.</li>
                </ul>
            </div>            
            <p class="{{ themeClass('text-2') }} mb-4 mt-10">
                Nous attendons vos retours sur :<br>
                - L'ergonomie de l'interface<br>
                - L'utilité des fonctionnalités existantes<br>
                - Toute suggestion d'amélioration
            </p>
            <p class="{{ themeClass('text-2') }} mb-2 font-medium">
                Utilisez le formulaire de contact intégré pour nous faire part de vos remarques à tout moment.
            </p>
            <p class="{{ themeClass('text-2') }}">
                Merci pour votre participation et votre confiance !<br>
                Laurent
            </p>
            <div class="text-center mt-8">
                <button id="closeWelcomeModal" class="{{ themeClass('button-accent') }} py-2 px-6 rounded-lg">
                    J'ai compris
                </button>
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
    
    .home-content-container {
        overflow-y: auto;
        scrollbar-width: none;
    }
    
    .home-content-container::-webkit-scrollbar {
        width: 0;
        display: none;
    }
    
    .modal-content {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .modal-content::-webkit-scrollbar {
        width: 0;
        display: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal functionality
        const modal = document.getElementById('welcomeModal');
        const openButton = document.getElementById('openWelcomeModal');
        const closeButton = document.getElementById('closeWelcomeModal');
        
        // Open modal function
        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        
        // Close modal function
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        
        // Add event listeners if elements exist
        if (openButton) openButton.addEventListener('click', openModal);
        if (closeButton) closeButton.addEventListener('click', closeModal);
        
        // Close modal when clicking outside the content
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });
        }
        
        // Animations on page load
        setTimeout(() => {
            document.getElementById('welcomeSection').classList.remove('opacity-0', 'translate-y-10');
            
            setTimeout(() => {
                document.getElementById('nextTrainingCard').classList.remove('opacity-0');
                
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
        }, 300);
        
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
@endsection