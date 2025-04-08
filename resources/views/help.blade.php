@extends('layouts.app')
@section('content')
<div class="help-container mx-auto p-4 md:p-8 max-w-7xl min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))]">
    <!-- Header Section -->
    <div class="{{ themeClass('card') }} backdrop-blur-lg rounded-xl shadow-lg mb-8 py-6 border">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold {{ themeClass('text-1') }} mb-3">User Guide</h1>
            <p class="text-lg md:text-xl {{ themeClass('text-secondary', 'text-gray-200') }} max-w-2xl mx-auto mb-4">
                Discover how to get the most out of your training calendar
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <a href="#overview" class="px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-play"></i> Get Started
                </a>
                <a href="{{ route('calendar') }}" class="px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar"></i> Go to Calendar
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Pills -->
    <div class="mb-8 p-3 {{ themeClass('card') }} rounded-lg border">
        <div class="flex flex-wrap justify-center gap-2">
            <a href="#overview" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-bullseye"></i> Overview
            </a>
            <a href="#training-plan" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-tasks"></i> Create Your Plan
            </a>
            <a href="#metrics" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-chart-bar"></i> Metrics
            </a>
            <a href="#strava" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-sync"></i> Strava
            </a>
            <a href="#week" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-calendar-week"></i> Weeks
            </a>
            <a href="#visual" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-palette"></i> Visual Guide
            </a>
            <a href="#tips" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-lightbulb"></i> Pro Tips
            </a>
            <a href="#data" class="whitespace-nowrap px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-database"></i> Data
            </a>
        </div>
    </div>

    <!-- Features Quick Access -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="feature-card {{ themeClass('card') }} border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-orange-500 bg-opacity-25">
                <i class="fab fa-strava text-orange-400"></i>
            </div>
            <h3 class="text-sm font-medium {{ themeClass('text-1') }}">Strava Sync</h3>
        </div>
        
        <div class="feature-card {{ themeClass('card') }} border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-teal-500 bg-opacity-25">
                <i class="fas fa-calendar text-teal-400"></i>
            </div>
            <h3 class="text-sm font-medium {{ themeClass('text-1') }}">Weekly Planning</h3>
        </div>
        
        <div class="feature-card {{ themeClass('card') }} border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-purple-500 bg-opacity-25">
                <i class="fas fa-chart-line text-purple-400"></i>
            </div>
            <h3 class="text-sm font-medium {{ themeClass('text-1') }}">Performance Tracking</h3>
        </div>
        
        <div class="feature-card {{ themeClass('card') }} border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-blue-500 bg-opacity-25">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <h3 class="text-sm font-medium {{ themeClass('text-1') }}">Pro Tips</h3>
        </div>
    </div>

    <!-- Main Content Sections -->
    <div class="space-y-16">
        <!-- Overview Section -->
        <section id="overview" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-bullseye text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Overview</h2>
            </div>
            
            <p class="{{ themeClass('text-2') }} text-lg mb-6">
                This calendar combines your actual sports activities (synced from Strava) 
                with your planned training sessions.
            </p>
            
            <div class="grid md:grid-cols-2 gap-6">
                <ul class="list-none space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-check-circle"></i></div>
                        <span class="{{ themeClass('text-1') }}">View your weekly/monthly/yearly training load</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-check-circle"></i></div>
                        <span class="{{ themeClass('text-1') }}">Compare planned and actual performances</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-check-circle"></i></div>
                        <span class="{{ themeClass('text-1') }}">Organize training cycles with week types</span>
                    </li>
                </ul>
                
                <ul class="list-none space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-check-circle"></i></div>
                        <span class="{{ themeClass('text-1') }}">Drag and drop training sessions</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-check-circle"></i></div>
                        <span class="{{ themeClass('text-1') }}">Track progress with visual indicators</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-check-circle"></i></div>
                        <span class="{{ themeClass('text-1') }}">Adapt your schedule based on your goals</span>
                    </li>
                </ul>
            </div>
        </section>

        <!-- Creating Training Plan Section -->
        <section id="training-plan" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Create Your Training Plan</h2>
            </div>
            
            <div class="aspect-video rounded-lg overflow-hidden mb-6">
                <iframe 
                    class="w-full h-full"
                    src="https://www.youtube.com/embed/cNzBhZSOe78" 
                    title="YouTube video player" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            
            <p class="{{ themeClass('text-1') }} text-lg">
                Watch this video for useful tips on creating your own training plan.
                This is the approach I personally use to structure my training sessions, but of course, it's just one method among many.
                Feel free to use it for inspiration or adapt it to your own needs!
            </p>
        </section>

        <!-- Performance Metrics Section -->
        <section id="metrics" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Performance Metrics</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center {{ themeClass('card') }} p-5 rounded-xl">
                    <div class="w-16 h-16 mx-auto bg-blue-300 bg-opacity-25 backdrop-filter backdrop-blur-sm rounded-xl flex items-center justify-center mb-3">
                        <i class="fas fa-route text-blue-800 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold {{ themeClass('text-1') }}">Distance</h3>
                    <p class="text-sm {{ themeClass('text-2') }} mt-2">Complete tracking of distance covered compared to your goals</p>
                </div>
                
                <div class="text-center {{ themeClass('card') }} p-5 rounded-xl">
                    <div class="w-16 h-16 mx-auto bg-red-300 bg-opacity-25 backdrop-filter backdrop-blur-sm rounded-xl flex items-center justify-center mb-3">
                        <i class="fas fa-mountain text-red-800 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold {{ themeClass('text-1') }}">Elevation</h3>
                    <p class="text-sm {{ themeClass('text-2') }} mt-2">Precise analysis of climbs and positive elevation gain</p>
                </div>
                
                <div class="text-center {{ themeClass('card') }} p-5 rounded-xl">
                    <div class="w-16 h-16 mx-auto bg-green-300 bg-opacity-25 backdrop-filter backdrop-blur-sm rounded-xl flex items-center justify-center mb-3">
                        <i class="fas fa-stopwatch text-green-800 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold {{ themeClass('text-1') }}">Time</h3>
                    <p class="text-sm {{ themeClass('text-2') }} mt-2">Training time management and comparison with planned sessions</p>
                </div>
            </div>
            
            <div class="{{ themeClass('card') }} p-5 rounded-xl">
                <h3 class="text-lg font-semibold {{ themeClass('text-1') }} mb-3">Metric Color Coding</h3>
                <p class="{{ themeClass('text-1') }}">
                    <span class="font-semibold text-blue-500">Blue</span> = Distance,
                    <span class="font-semibold text-red-500 ms-1">Red</span> = Elevation, 
                    <span class="font-semibold text-green-500 ms-1">Green</span> = Duration<br>
                    Format: <span class="font-semibold">Actual / Planned</span>
                </p>
                
                <div class="mt-4 p-3 bg-black bg-opacity-20 rounded-lg">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm {{ themeClass('text-1') }}">Progress Example</span>
                        <span class="text-xs {{ themeClass('text-2') }}">70%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-700 rounded-full">
                        <div class="h-2 bg-green-400 rounded-full" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Strava Sync Section -->
        <section id="strava" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-sync text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Strava Synchronization</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2">
                    <p class="{{ themeClass('text-1') }} mb-4">
                        Click to sync your latest Strava activities. A loading indicator appears during synchronization.
                    </p>
                    <p class="{{ themeClass('text-1') }} mb-4">
                        When connecting to the site, users are prompted to consent to connecting with Strava and authorize access to their activities.
                    </p>
                    <div class="bg-yellow-900 bg-opacity-20 border border-yellow-600 border-opacity-20 p-4 rounded-lg mt-4">
                        <p class="text-sm text-yellow-300 flex items-start gap-2">
                            <i class="fas fa-exclamation-triangle mt-1"></i>
                            <span>Only "Run" type activities are imported</span>
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center {{ themeClass('card') }} p-5 rounded-xl">
                    <div class="w-16 h-16 bg-orange-500 bg-opacity-20 backdrop-filter backdrop-blur-sm rounded-xl flex items-center justify-center mb-3">
                        <i class="fab fa-strava text-3xl text-orange-400"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Week Management Section -->
        <section id="week" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Week Management</h2>
            </div>
            
            <div class="mb-6 relative">
                <div class="border rounded-lg overflow-hidden">
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
                                            <option value="">Development</option>
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
                                            4h15
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
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold {{ themeClass('text-1') }} mb-4">Main Features</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-mouse-pointer"></i></div>
                            <span class="{{ themeClass('text-1') }}">Click on any day to add training sessions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-edit"></i></div>
                            <span class="{{ themeClass('text-1') }}">Click on a training badge to edit session details</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-tag"></i></div>
                            <span class="{{ themeClass('text-1') }}">Use week types to categorize training phases</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-arrows-alt"></i></div>
                            <span class="{{ themeClass('text-1') }}">Drag and drop sessions between days</span>
                        </li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold {{ themeClass('text-1') }} mb-4">Advanced Tips</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-clone"></i></div>
                            <span class="{{ themeClass('text-1') }}">Use Ctrl + drag and drop to copy sessions</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-trash"></i></div>
                            <span class="{{ themeClass('text-1') }}">Use the trash icon to delete entire weeks</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-info-circle"></i></div>
                            <span class="{{ themeClass('text-1') }}">Hover over icons for detailed tooltips</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 {{ themeClass('text-link') }}"><i class="fas fa-calendar-day"></i></div>
                            <span class="{{ themeClass('text-1') }}">Double-click on a day to quickly see details</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Visual Guide Section -->
        <section id="visual" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-palette text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Visual Guide</h2>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="{{ themeClass('card') }} rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-running text-white"></i>
                        </div>
                        <h3 class="font-medium {{ themeClass('text-1') }}">Recorded Strava Activity</h3>
                    </div>
                    <p class="text-sm {{ themeClass('text-2') }}">
                        Indicates an activity you've completed and synced from Strava
                    </p>
                </div>
                
                <div class="{{ themeClass('card') }} rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-running text-white"></i>
                        </div>
                        <h3 class="font-medium {{ themeClass('text-1') }}">Planned Training Session</h3>
                    </div>
                    <p class="text-sm {{ themeClass('text-2') }}">
                        Represents a planned session (color and icon varies by training type)
                    </p>
                </div>
                
                <div class="{{ themeClass('card') }} rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 border-2 border-blue-300 bg-blue-900 bg-opacity-30 rounded-lg flex-shrink-0"></div>
                        <h3 class="font-medium {{ themeClass('text-1') }}">Current Day</h3>
                    </div>
                    <p class="text-sm {{ themeClass('text-2') }}">
                        Shows the present day in the calendar for easy orientation
                    </p>
                </div>
                
                <div class="{{ themeClass('card') }} rounded-xl p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-full flex flex-col gap-1 flex-shrink-0">
                            <div class="h-2 bg-gray-700 rounded-full">
                                <div class="h-2 bg-blue-400 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                    <h3 class="font-medium {{ themeClass('text-1') }} mb-2">Goal Progress</h3>
                    <p class="text-sm {{ themeClass('text-2') }}">
                        Visually indicates your progress toward set goals
                    </p>
                </div>
            </div>
        </section>

        <!-- Pro Tips Section -->
        <section id="tips" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-lightbulb text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Pro Tips</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <div class="bg-green-900 bg-opacity-20 border border-green-600 border-opacity-20 p-5 rounded-xl">
                    <h3 class="font-semibold {{ themeClass('text-1') }} mb-3">Best Practices</h3>
                    <ul class="space-y-2 {{ themeClass('text-1') }}">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>Sync with Strava after each run</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>Use color-coded week types for training phases</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>Build your training weeks starting from your race goal</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>Use a reduced week every 3 or 4 weeks</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>Listen to your body and adjust plans accordingly</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>80% easy runs, 20% hard efforts for optimal results</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>Recovery is as important as training sessions</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-green-400"></i>
                            <span>Consistency beats intensity for long-term progress</span>
                        </li>
                    </ul>
                </div>      
                
                <div class="bg-blue-900 bg-opacity-20 border border-blue-600 border-opacity-20 p-5 rounded-xl">
                    <h3 class="font-semibold {{ themeClass('text-1') }} mb-3">Training Types</h3>
                    <ul class="space-y-2 {{ themeClass('text-1') }}">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-blue-400"></i>
                            <span><span class="font-medium">Easy Run:</span> A comfortable run, low intensity to develop aerobic endurance without excessive fatigue</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-blue-400"></i>
                            <span><span class="font-medium">Recovery Run:</span> A very light run performed after intense training to promote muscle recovery</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-blue-400"></i>
                            <span><span class="font-medium">Intervals:</span> Intense periods of running followed by rest or slow jogging to improve speed and cardiovascular condition</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-blue-400"></i>
                            <span><span class="font-medium">Long Run:</span> A sustained run at moderate pace to develop endurance and mental resilience</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-blue-400"></i>
                            <span><span class="font-medium">Fartlek:</span> A mix of fast and slow running, with spontaneous speed variations to improve adaptability</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-blue-400"></i>
                            <span><span class="font-medium">Tempo Run:</span> A sustained, moderately intense effort to improve lactate threshold and race performance</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-blue-400"></i>
                            <span><span class="font-medium">Hill Repeats:</span> Short intense uphill sprints followed by recovery jogs to develop strength and power</span>
                        </li>
                    </ul>
                </div>
                
                <div class="bg-red-900 bg-opacity-20 border border-red-600 border-opacity-20 p-5 rounded-xl">
                    <h3 class="font-semibold {{ themeClass('text-1') }} mb-3">Week Types</h3>
                    <ul class="space-y-2 {{ themeClass('text-1') }}">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-red-400"></i>
                            <span><span class="font-medium">Reduced:</span> A week with reduced volume to prevent burnout and allow adaptation</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-red-400"></i>
                            <span><span class="font-medium">Recovery:</span> A light intensity week focused on rest and active recovery</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-red-400"></i>
                            <span><span class="font-medium">Development:</span> A high-load week designed to improve endurance, speed, or strength</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-red-400"></i>
                            <span><span class="font-medium">Maintenance:</span> A balanced week that maintains fitness without excessive stress</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-circle text-xs mt-1.5 text-red-400"></i>
                            <span><span class="font-medium">Taper:</span> A progressive reduction in training volume before a race</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Data Controls Section -->
        <section id="data" class="section-card {{ themeClass('card') }} backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full {{ themeClass('button') }} flex items-center justify-center">
                    <i class="fas fa-database text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold {{ themeClass('text-1') }}">Data Control</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="{{ themeClass('text-1') }} mb-4">
                        Use deletion options with caution:
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-start gap-3">
                            <div class="mt-1 text-red-400"><i class="fas fa-trash-alt"></i></div>
                            <span class="{{ themeClass('text-1') }}">Delete individual sessions from the training session view</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 text-red-400"><i class="fas fa-calendar-times"></i></div>
                            <span class="{{ themeClass('text-1') }}">Clear entire weeks/months/years using the trash icons</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 text-red-400"><i class="fas fa-running"></i></div>
                            <span class="{{ themeClass('text-1') }}">Remove activities from the activities menu</span>
                        </li>
                    </ul>
                </div>
                
                <div class="flex flex-col gap-4">
                    <div class="bg-red-900 bg-opacity-20 border border-red-600 border-opacity-20 p-5 rounded-xl flex items-center gap-4">
                        <div class="text-red-400 text-3xl">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold {{ themeClass('text-1') }} mb-1">Warning</h3>
                            <p class="text-sm {{ themeClass('text-2') }}">
                                Deleted data cannot be recovered
                            </p>
                        </div>
                    </div>
                    
                    <div class="{{ themeClass('card') }} p-5 rounded-xl">
                        <h3 class="font-medium {{ themeClass('text-1') }} mb-3">Advanced Actions</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                            <button class="px-4 py-2 {{ themeClass('button') }} rounded-lg transition-all duration-300 flex items-center justify-center gap-2">
                                <i class="fas fa-trash"></i> Clear All
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    html, body {
        height: 100%;
        overflow-y: auto;
        margin: 0;
        padding: 0;
    }
    
    .help-container {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .help-container::-webkit-scrollbar {
        display: none;
    }
    
    .help-header {
        position: relative;
        padding: 3rem 0;
        overflow: hidden;
        border-radius: 1rem;
    }
    
    .help-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.7) 0%, rgba(16, 24, 39, 0.8) 100%);
        z-index: -2;
    }
    
    .feature-icon {
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .feature-card:hover .feature-icon {
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
    }
    
    .section-card {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .section-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 1s ease;
    }
    
    .section-card:hover::after {
        transform: translateX(100%);
    }
    
    .section-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    @media (min-width: 1024px) {
        .two-columns {
            column-count: 2;
            column-gap: 2rem;
        }
    }
</style>
@endsection