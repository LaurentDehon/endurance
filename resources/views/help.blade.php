@extends('layouts.app')
@section('content')
<div class="mx-auto p-4 md:p-8 max-w-5xl min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))]">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 md:mb-8">Help - Training Calendar Guide</h1>

    <div class="space-y-3 md:space-y-4">    
        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Overview</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Overview Section -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">🎯 Overview</h2>
                        <p class="text-gray-600 mb-4">
                            This calendar combines your actual sports activities (synced from Strava) 
                            with your planned training sessions. Key features include:
                        </p>
                        <ul class="list-disc pl-6 text-gray-600 space-y-2">
                            <li>Visualize weekly/monthly/yearly training load</li>
                            <li>Compare planned vs actual performance</li>
                            <li>Organize training cycles with week types</li>
                            <li>Drag-and-drop training sessions</li>
                            <li>Progress tracking with visual indicators</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>

        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Creating your own Training Plan</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Stats Section -->
                    <section>
                        <div class="aspect-video rounded-lg overflow-hidden mb-4">
                            <iframe 
                                class="w-full h-full"
                                src="https://www.youtube.com/embed/cNzBhZSOe78" 
                                title="YouTube video player" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        </div>
                        <p class="text-gray-600">
                            Watch this video guide for useful tips on creating your own training plan. 
                            This is the approach I personally use to structure my workouts, but of course, it's just one method among many. 
                            Feel free to take inspiration from it or adapt it to your own needs!
                        </p>
                    </section>
                </div>
            </div>
        </div>

        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Performance Metrics</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Stats Section -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">📊 Performance Metrics</h2>
                        <div class="bg-white p-6 rounded-xl shadow-lg mb-4">
                            <div class="flex gap-8">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-2">
                                        <i class="fas fa-route text-blue-600"></i>
                                    </div>
                                    <span class="text-sm text-gray-500">Distance</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-2">
                                        <i class="fas fa-mountain text-red-600"></i>
                                    </div>
                                    <span class="text-sm text-gray-500">Elevation</span>
                                </div>
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-2">
                                        <i class="fas fa-stopwatch text-green-600"></i>
                                    </div>
                                    <span class="text-sm text-gray-500">Time</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-600">
                            Color-coded metrics:
                            <span class="font-semibold text-blue-600">Blue</span> = Distance,
                            <span class="font-semibold text-red-600">Red</span> = Elevation,
                            <span class="font-semibold text-green-600">Green</span> = Duration<br>
                            Format: <span class="font-semibold">Actual / Planned</span>
                        </p>
                    </section>
                </div>
            </div>
        </div>

        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Strava Synchronization</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Sync Section -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">🔄 Strava Synchronization</h2>
                        <div class="flex items-center gap-4 mb-4">
                            <button class="w-12 btn bg-orange-100 text-orange-600 p-3 rounded-xl">
                                <i class="fas fa-sync"></i>
                            </button>
                        </div>
                        <p class="text-gray-600">
                            Click to sync your latest Strava activities. A loading indicator appears during synchronization.
                        </p>
                        <p class="text-gray-600">
                            When logging into the site, the user is asked for their consent to connect to Strava and grant access to their activities.
                        </p>
                        <div class="bg-yellow-100 p-4 rounded-lg mt-4">
                            <p class="text-sm text-yellow-800">
                                ⚠️ Only "Run" activities are imported
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Week Management</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Week Management -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">📅 Week Management</h2>
                        <div class="mb-4 relative">
                            <div class="border rounded-lg p-4">
                                <div class="week-header px-3 py-2 bg-gradient-to-bl from-blue-800 via-blue-700 to-blue-800 border-b flex items-center gap-4 flex-wrap">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-block px-3 py-1 text-sm font-medium rounded bg-gray-100 text-gray">
                                                Week 16
                                            </span>                                        
                                            <span class="text-sm text-gray-100">
                                                14 Apr - 20 Apr
                                            </span>
                                        </div>
                                    
                                        <div class="h-9">
                                            <div class="flex items-center gap-2">
                                                <div class="relative">
                                                    <select class="bg-gray-100 appearance-none block pl-8 pr-10 py-1.5 text-sm rounded-md border focus:outline-none focus:ring-0 focus:border-gray-300">
                                                        <option value="">Developpment</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 left-2 flex items-center">
                                                        <i class="fas fa-tag text-gray-400"></i>
                                                    </div>
                                                </div>
                                                <button class="mx-2 text-gray-100 hover:text-gray-300">
                                                    <i class="fas fa-trash-alt"></i>                                      
                                                </button>
                                            </div>
                                        </div>
                                    </div>                                                      
    
                                    <!-- Week stats -->
                                    <div class="flex flex-col md:flex-row gap-4 md:gap-6 mt-4 md:ml-auto">
                                        @foreach(['distance', 'elevation', 'time'] as $stat)
                                            <div class="text-center min-w-[120px] md:min-w-[160px]">
                                                @php
                                                    $icon = match($stat) {
                                                        'distance' => 'route',
                                                        'elevation' => 'mountain',
                                                        'time' => 'stopwatch',
                                                    };
                                                @endphp
                                                <p class="text-s text-gray-300 mb-1">
                                                    <i class="fas fa-{{ $icon }} mr-1"></i>{{ ucfirst($stat) }}
                                                </p>
                                                <p class="text-xl font-bold text-gray-100 mb-1">
                                                    @if($stat === 'distance')
                                                        43.2
                                                    @elseif($stat === 'time')
                                                        4h50
                                                    @else
                                                        332
                                                    @endif
                                                    
                                                    @if($stat === 'time')
                                                        <span class="text-sm">/ 
                                                            @if($stat === 'time')
                                                                4h35
                                                            @endif
                                                        </span>
                                                    @endif
                                                    @if($stat === 'time')
                                                        <div class="w-full h-1.5 bg-gray-200 rounded-full">
                                                            <div class="h-1.5 bg-green-300 rounded-full" 
                                                                style="width: 90%"></div>
                                                        </div>
                                                    @endif
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="list-disc pl-6 text-gray-600 space-y-2">
                            <li>Click any day to add a training sessions</li>
                            <li>Click a training badge to edit session details</li>
                            <li>Use week types to categorize training phases</li>
                            <li>Drag-and-drop sessions between days</li>
                            <li>Ctrl drag-and-drop to copy sessions</li>
                            <li>Use the trash icon to clear entire weeks</li>
                            <li>Hover over icons for detailed tooltips</li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>

        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Visual Guide</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Visual Indicators -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-6">🎨 Visual Guide</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                            <div class="flex gap-2">
                                <div class="w-6 h-6 bg-orange-500 rounded-full"></div>
                                <span>Recorded Strava activity</span>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-6 h-6 bg-blue-500 rounded-full"></div>
                                <span>Planned training session (color is based on training session type)</span>
                            </div>
                            <div class="flex gap-2">
                                <div class="w-8 h-8 border-2 border-blue-300 bg-blue-50 rounded-lg"></div>
                                <span>Current day</span>
                            </div>                            
                            <div class="flex flex-col gap-2">
                                {{-- <div class="w-full h-1.5 bg-blue-200 rounded-full">
                                    <div class="w-3/4 h-full bg-blue-500 rounded-full"></div>
                                </div> --}}
                                <div class="w-1/2 h-1.5 bg-gray-200 rounded-full">
                                    <div class="h-1.5 bg-blue-300 rounded-full" style="width: 60%"></div>
                                </div>
                                <span>Progress toward goal</span>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Pro Tips</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Pro Tips -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">💡 Pro Tips</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">                            
                            <div class="bg-green-50 p-3 md:p-4 rounded-lg">
                                <h3 class="font-semibold mb-2">Best Practices</h3>
                                <ul class="space-y-2">
                                    <li>Sync with Strava after every run</li>
                                    <li>Use color-coded week types for training phases</li>
                                    <li>Build your training weeks by starting from your race goal and working backward</li>
                                    <li>Use reduced week once every 3 or 4 weeks</li>
                                </ul>
                            </div>
                            <div class="bg-blue-50 p-3 md:p-4 rounded-lg md:col-span-2 lg:col-span-1">
                                <h3 class="font-semibold mb-2">Training types</h3>
                                <ul class="space-y-2">
                                    <li>Easy Run : A comfortable, low-intensity run to build aerobic endurance without excessive fatigue</li>
                                    <li>Recovery Run : A very light run done after intense workouts to promote muscle recovery</li>
                                    <li>Intervals : High-intensity bursts of running followed by rest or slow jogging to improve speed and cardiovascular fitness</li>
                                    <li>Long Run : A sustained, moderate-paced run to build endurance and mental resilience</li>
                                    <li>Fartlek : A mix of fast and slow running, incorporating spontaneous speed variations to improve adaptability</li>
                                    <li>Tempo Run : A sustained, moderately hard effort (comfortably hard) to enhance lactate threshold and race performance</li>
                                    <li>Hill Repeats : Short, intense uphill sprints followed by recovery jogs to develop strength and power</li>
                                </ul>
                            </div>
                            <div class="bg-red-50 p-3 md:p-4 rounded-lg">
                                <h3 class="font-semibold mb-2">Week types</h3>
                                <ul class="space-y-2">
                                    <li>Reduced : A lower-volume week to prevent burnout and allow adaptation</li>
                                    <li>Recovery : A light-intensity week focused on rest and active recovery after intense training or after a race</li>
                                    <li>Development : A high-load week designed to improve endurance, speed, or strength</li>
                                    <li>Maintain : A balanced week that sustains fitness without excessive stress</li>
                                    <li>Taper : A gradual reduction in training volume before a race to maximize performance</li>
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div x-data="{ isOpen: false }" class="space-y-2">
            <button @click="isOpen = !isOpen" :aria-expanded="isOpen" aria-controls="collapse-content" class="flex items-center justify-between w-full px-4 py-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="font-medium text-indigo-700">Data Controls</span>                
                <svg class="w-5 h-5 text-indigo-600 transform transition-transform duration-300" :class="{ 'rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">                
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>        
            <div id="collapse-content" x-show="isOpen" x-collapse
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-y-0 -translate-y-2"
                x-transition:enter-end="opacity-100 transform scale-y-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-y-100"
                x-transition:leave-end="opacity-0 transform scale-y-0" class="ml-4 pl-3 border-l-4 border-indigo-200 bg-white rounded-r-lg shadow-sm" :class="{ 'bg-indigo-50 border-indigo-400': isOpen }">
                <div class="p-4 space-y-3">
                    <!-- Data Management -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-800 mb-4">🧹 Data Controls</h2>
                        <div class="flex gap-4 mb-4">
                            <button class="w-12 btn bg-red-100 text-red-600 p-3 rounded-xl">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <p class="text-gray-600">
                            Carefully use deletion options:
                        </p>
                        <ul class="list-disc pl-6 text-gray-600 space-y-2 mt-2">
                            <li>Delete individual sessions from the training session view</li>
                            <li>Clear entire weeks/months/years using trash icons</li>
                            <li>Delete activities from the activities menu</li>
                        </ul>
                        <div class="bg-red-100 p-4 rounded-lg mt-4">
                            <p class="text-sm text-red-800">
                                ⚠️ Deleted data cannot be recovered
                            </p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection