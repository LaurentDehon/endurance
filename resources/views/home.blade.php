@extends('layouts.app')

@section('title', 'Zone 2 - Running Training Plan Application')
@section('meta_description', 'Zone 2 helps you create personalized and structured training plans for running. Plan your weeks, sync with Strava, and optimize your progression.')
@section('meta_keywords', 'running training plan, running planning, marathon, trail, periodization, zone 2, running progression, running calendar')
@section('og_title', 'Zone 2 - Take Control of Your Running Training')
@section('og_description', 'Create personalized training plans, structured in blocks and tailored to your goals. Analyze your performance and sync with Strava.')

@section('content')
<div class="welcome-container overflow-y-auto w-full">
    <div class="mx-auto px-4 py-8 max-w-7xl">
        <!-- Hero Section -->
        <div class="hero-section bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl shadow-lg mb-12 p-8 border">
            <div class="container mx-auto">
                <div class="flex flex-col md:flex-row items-center gap-12">
                    <div class="md:w-full">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white">Take Control of Your Training</h1>
                        <p class="text-xl text-cyan-200 mb-6">
                            Zone 2 helps you create personalized, structured training plans tailored to your goals.
                            A clear, progressive approach that you control.
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="@auth {{ route('calendar') }} @else {{ route('login') }} @endauth" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-running mr-2"></i> @auth Create My Training Plan @else Sign In to Start @endauth
                            </a>
                            <a href="#features" class="px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-search mr-2"></i> Discover Features
                            </a>
                            <a href="{{ route('help') }}" class="px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-question-circle mr-2"></i> Help Center
                            </a>
                        </div>
                    </div>
                    {{-- <div class="md:w-1/3 flex justify-center">
                        <div class="w-64 h-64 bg-gradient-to-br from-cyan-500 to-blue-700 rounded-full flex items-center justify-center shadow-2xl">
                            <span class="text-6xl font-bold text-white">Z2</span>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Why Zone 2 Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="whySection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <h2 class="text-3xl font-bold text-white mb-6">Why Zone 2?</h2>
                <p class="text-lg text-cyan-200 mb-6">
                    Many runners still plan their workouts in Excel spreadsheets or on paper.<br>
                    Zone 2 offers an interactive and structured alternative, designed for those who want to track their progress methodically.
                </p>
                
                <div class="bg-blue-900 bg-opacity-30 border border-blue-400 border-opacity-20 p-5 rounded-xl mb-8">
                    <p class="text-xl text-center font-semibold text-white">
                        Zone 2 isn't a plan generator, it's your smart training companion that evolves with you.
                    </p>
                </div>   
            </div>
        </section>

        <!-- Structure Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="structureSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cube text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">A Structure Designed for Progression</h2>
                </div>
                
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-full">
                        <h3 class="text-2xl font-bold text-white mb-4">Think in Blocks, Progress Intelligently</h3>
                        <p class="text-lg text-cyan-200 mb-4">
                            Zone 2 is based on structured periodization. Each week plays a specific role in your progression.
                        </p>
                        <p class="text-lg text-cyan-200 mb-6">
                            This method helps balance training load and optimize performance.
                        </p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-pink-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-bed text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">Recovery</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    A light intensity week focused on rest and active recovery
                                </p>
                            </div>

                            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-arrow-up text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">Development</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    A high-load week designed to improve endurance, speed, or strength
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-amber-700 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-equals text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">Maintain</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    A balanced week that maintains fitness without excessive stress
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-emerald-400 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-arrow-down text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">Reduced</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    A week with reduced volume to prevent burnout and allow adaptation
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-fuchsia-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-compress-alt text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">Taper</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    A progressive reduction in workout volume before a race
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-rose-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-flag-checkered text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">Race</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    Competition week including the race and recovery
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="md:w-1/2 flex justify-center">
                        <img src="{{ asset('images/progression-chart.jpg') }}" alt="Training Structure" class="rounded-xl shadow-lg max-w-full h-auto" onerror="this.src='https://via.placeholder.com/500x350?text=Training+Structure';this.onerror='';">
                    </div> --}}
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-tools text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">Key Features</h2>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-6">Everything You Need to Plan, Track, and Adjust</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-cyan-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-calendar-alt text-cyan-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">Interactive Calendar</h4>
                        <p class="text-cyan-200">View your plan by week and month with an intuitive interface</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-yellow-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-bullseye text-yellow-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">Precise Goal Setting</h4>
                        <p class="text-cyan-200">Set your targets in duration, distance, and elevation for each session</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-orange-500 bg-opacity-25 rounded-full">
                            <i class="fab fa-strava text-orange-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">Strava Synchronization</h4>
                        <p class="text-cyan-200">Automatically import your activities to compare with your plan</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-purple-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-chart-bar text-purple-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">Dashboards</h4>
                        <p class="text-cyan-200">Analyze your performance and compare planned vs. actual results</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-teal-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-dumbbell text-teal-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">Custom Week Types</h4>
                        <p class="text-cyan-200">Adapt your weeks according to your specific training phases</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-red-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">Overload Alert</h4>
                        <p class="text-cyan-200">Receive notifications if you exceed the 10% increase rule</p>
                    </div>

                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-12 h-12 flex items-center justify-center bg-indigo-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-puzzle-piece text-indigo-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">Coherent Block Structure</h4>
                        <p class="text-cyan-200">Organize your weeks based on your specific goals</p>
                    </div>

                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-12 h-12 flex items-center justify-center bg-blue-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-pencil-alt text-blue-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">100% Customized Plans</h4>
                        <p class="text-cyan-200">Create and adapt your plan according to your specific needs</p>
                    </div>

                    <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-12 h-12 flex items-center justify-center bg-pink-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-eye text-pink-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">Clear Annual Planning</h4>
                        <p class="text-cyan-200">Visualize and organize your training year in just a few clicks</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="howSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-question text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">How It Works</h2>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-6">Your Training in 5 Steps</h3>
                
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="md:w-full">
                        <div class="steps-container relative">
                            <div class="steps-line absolute left-6 top-8 bottom-11 w-0.5 bg-cyan-500 z-0"></div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">1</div>
                                <div class="step-content bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">Create Your Goal</h4>
                                    <p class="text-cyan-200">Define your main objective (e.g., Marathon, 50K Trail) and the event date</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">2</div>
                                <div class="step-content bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">Organize Your Weeks</h4>
                                    <p class="text-cyan-200">Structure your weeks in blocks adapted to your goal (development, maintenance, recovery...)</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">3</div>
                                <div class="step-content bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">Add Your Sessions</h4>
                                    <p class="text-cyan-200">Plan your weekly sessions with details (type, distance, duration, elevation)</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">4</div>
                                <div class="step-content bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">Sync with Strava</h4>
                                    <p class="text-cyan-200">Connect your Strava account or manually enter your completed activities</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">5</div>
                                <div class="step-content bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">Track and Adjust</h4>
                                    <p class="text-cyan-200">Modify your plan based on how you feel and your actual performance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- <div class="md:w-1/3 flex justify-center">
                        <img src="{{ asset('images/zone2-app.jpg') }}" alt="Zone 2 Interface" class="rounded-xl shadow-lg max-w-full h-auto" onerror="this.src='https://via.placeholder.com/400x600?text=Zone+2+Interface';this.onerror='';">
                    </div> --}}
                </div>
            </div>
        </section>        

        <!-- Training Blocks Section -->
        <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border mb-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-route text-xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white">16-Week Marathon Training Plan Example</h2>
            </div>
            
            <div class="bg-blue-900 bg-opacity-30 border border-blue-400 border-opacity-20 p-5 rounded-xl mb-8">
                <p class="text-lg italic text-white">
                    "A strategic progression from initial development to race day, with regular recovery periods to optimize adaptation."
                </p>
            </div>
            
            <div class="space-y-6">
                <!-- Weeks 1-3: Development -->
                <div class="bg-blue-600 bg-opacity-20 p-5 rounded-xl border border-blue-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-blue-600 text-white text-sm rounded-full flex-shrink-0">Weeks 1-3</span>
                        <i class="fas fa-arrow-up text-blue-300 mr-2"></i> Base Building (Development)
                    </h3>
                    <p class="text-blue-200 mb-3">Development phase focused on building aerobic foundation</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-chart-line mr-2"></i> Progressive volume increase week by week</li>
                        <li><i class="fas fa-heartbeat mr-2"></i> Low-intensity foundation work to build endurance</li>
                        <li><i class="fas fa-clock mr-2"></i> Establishing running routine and consistency</li>
                    </ul>
                </div>
                
                <!-- Week 4: Reduced -->
                <div class="bg-emerald-600 bg-opacity-20 p-5 rounded-xl border border-emerald-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-emerald-600 text-white text-sm rounded-full flex-shrink-0">Week 4</span>
                        <i class="fas fa-arrow-down text-emerald-300 mr-2"></i> First Recovery (Reduced)
                    </h3>
                    <p class="text-emerald-200 mb-3">Planned reduction in volume to allow adaptation</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-tachometer-alt mr-2"></i> 20-30% volume reduction from previous weeks</li>
                        <li><i class="fas fa-walking mr-2"></i> Focus on active recovery sessions</li>
                        <li><i class="fas fa-bed mr-2"></i> Extra rest and sleep emphasis</li>
                    </ul>
                </div>
                
                <!-- Weeks 5-7: Development -->
                <div class="bg-blue-600 bg-opacity-20 p-5 rounded-xl border border-blue-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-blue-600 text-white text-sm rounded-full flex-shrink-0">Weeks 5-7</span>
                        <i class="fas fa-arrow-up text-blue-300 mr-2"></i> Building Phase (Development)
                    </h3>
                    <p class="text-blue-200 mb-3">Progressive development with increasing intensity</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-fire mr-2"></i> Introduction of tempo runs and some speedwork</li>
                        <li><i class="fas fa-road mr-2"></i> Longer weekend runs to build endurance</li>
                        <li><i class="fas fa-chart-bar mr-2"></i> Building weekly mileage at a controlled rate</li>
                    </ul>
                </div>
                
                <!-- Week 8: Reduced -->
                <div class="bg-emerald-600 bg-opacity-20 p-5 rounded-xl border border-emerald-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-emerald-600 text-white text-sm rounded-full flex-shrink-0">Week 8</span>
                        <i class="fas fa-arrow-down text-emerald-300 mr-2"></i> Mid-cycle Recovery (Reduced)
                    </h3>
                    <p class="text-emerald-200 mb-3">Strategic recovery to consolidate fitness gains</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-battery-half mr-2"></i> Reduced mileage week to prevent overtraining</li>
                        <li><i class="fas fa-sync mr-2"></i> Maintain frequency, lower intensity</li>
                        <li><i class="fas fa-battery-three-quarters mr-2"></i> Preparation for peak training phase</li>
                    </ul>
                </div>
                
                <!-- Weeks 9-10: Development -->
                <div class="bg-blue-600 bg-opacity-20 p-5 rounded-xl border border-blue-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-blue-600 text-white text-sm rounded-full flex-shrink-0">Weeks 9-10</span>
                        <i class="fas fa-arrow-up text-blue-300 mr-2"></i> Peak Training (Development)
                    </h3>
                    <p class="text-blue-200 mb-3">Highest volume and intensity weeks of the plan</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-mountain mr-2"></i> Longest runs of training cycle (up to 20 miles)</li>
                        <li><i class="fas fa-tachometer-alt mr-2"></i> Race-specific workout intensity</li>
                        <li><i class="fas fa-brain mr-2"></i> Mental toughness development</li>
                    </ul>
                </div>
                
                <!-- Week 11: Reduced -->
                <div class="bg-emerald-600 bg-opacity-20 p-5 rounded-xl border border-emerald-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-emerald-600 text-white text-sm rounded-full flex-shrink-0">Week 11</span>
                        <i class="fas fa-arrow-down text-emerald-300 mr-2"></i> Pre-maintain Recovery (Reduced)
                    </h3>
                    <p class="text-emerald-200 mb-3">Final recovery before maintain phase</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-battery-quarter mr-2"></i> Strategic volume reduction to absorb peak weeks</li>
                        <li><i class="fas fa-redo mr-2"></i> Reset for final preparation phase</li>
                        <li><i class="fas fa-search mr-2"></i> Assessment of progress and adjustments</li>
                    </ul>
                </div>
                
                <!-- Weeks 12-13: Maintain -->
                <div class="bg-amber-600 bg-opacity-20 p-5 rounded-xl border border-amber-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-amber-600 text-white text-sm rounded-full flex-shrink-0">Weeks 12-13</span>
                        <i class="fas fa-equals text-amber-300 mr-2"></i> Maintain Phase
                    </h3>
                    <p class="text-amber-200 mb-3">Holding fitness while preparing for taper</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-balance-scale mr-2"></i> Consistent volume at 80-90% of peak</li>
                        <li><i class="fas fa-stopwatch mr-2"></i> Race pace work integration</li>
                        <li><i class="fas fa-chess mr-2"></i> Race day strategy refinement</li>
                    </ul>
                </div>
                
                <!-- Weeks 14-15: Taper -->
                <div class="bg-fuchsia-600 bg-opacity-20 p-5 rounded-xl border border-fuchsia-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-fuchsia-600 text-white text-sm rounded-full flex-shrink-0">Weeks 14-15</span>
                        <i class="fas fa-compress-alt text-fuchsia-300 mr-2"></i> Taper Period
                    </h3>
                    <p class="text-fuchsia-200 mb-3">Strategic reduction to maximize race day performance</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-sort-amount-down mr-2"></i> Progressive volume reduction (40-60%)</li>
                        <li><i class="fas fa-bolt mr-2"></i> Maintaining intensity while reducing volume</li>
                        <li><i class="fas fa-battery-full mr-2"></i> Recovery optimization and glycogen loading</li>
                    </ul>
                </div>
                
                <!-- Week 16: Race -->
                <div class="bg-rose-600 bg-opacity-20 p-5 rounded-xl border border-rose-500 border-opacity-30 shadow-lg md:w-3/4 lg:w-2/3 mx-auto">
                    <h3 class="text-xl font-bold text-white flex items-center mb-2">
                        <span class="mr-3 px-3 py-1 bg-rose-600 text-white text-sm rounded-full flex-shrink-0">Week 16</span>
                        <i class="fas fa-flag-checkered text-rose-300 mr-2"></i> Race Week
                    </h3>
                    <p class="text-rose-200 mb-3">Final preparation and race execution</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-feather mr-2"></i> Minimal running with rest focus</li>
                        <li><i class="fas fa-clipboard-check mr-2"></i> Race day logistics preparation</li>
                        <li><i class="fas fa-medal mr-2"></i> Marathon day!</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Beta Notice Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="betaSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-amber-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-flask text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">Beta Version</h2>
                </div>
                
                <div class="flex items-center gap-6 bg-amber-900 bg-opacity-30 border border-amber-500 border-opacity-20 p-5 rounded-xl mb-8">
                    <div class="text-amber-400 text-4xl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-lg text-white mb-2">Zone 2 is currently in beta version.</p>
                        <p class="text-white">The application is available in English, with additional languages planned for the final release. Some features are still under development.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="ctaSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border text-center">
                <h2 class="text-3xl font-bold text-white mb-6">Ready to Structure Your Progress?</h2>
                
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <a href="@auth {{ route('calendar') }} @else {{ route('login') }} @endauth" class="px-8 py-4 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center text-lg">
                        <i class="fas fa-check-circle mr-2"></i> @auth Create My Plan Now @else Sign In to Get Started @endauth
                    </a>
                    {{-- <button id="openDemoModal" class="px-8 py-4 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center text-lg">
                        <i class="fas fa-play-circle mr-2"></i> See Interface Demo
                    </button> --}}
                </div>
                
                @guest
                <div class="mt-4 flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="text-cyan-300 hover:underline">Log in</a>
                    <span class="text-white">•</span>
                    <a href="{{ route('register') }}" class="text-cyan-300 hover:underline">Create an account</a>
                </div>
                @endguest
            </div>
        </section>

        <!-- Logos Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="stravaSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl shadow-lg border flex justify-center items-center">
                <div class="w-60 h-60">
                    <img src="{{ asset('storage/images/strava-powered.svg') }}" alt="Strava Powered" class="w-full h-full">
                </div>
            </div>
        </section>
    </div>
</div>

<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    
    .welcome-container {
        min-height: 100%;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .welcome-container::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hidden::-webkit-scrollbar {
        display: none;
    }
    
    .scrollbar-hidden {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .benefit-icon, .feature-icon {
        transition: all 0.3s ease;
    }
    
    .benefit-card:hover .benefit-icon,
    .feature-card:hover .feature-icon {
        transform: scale(1.1);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.3);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate sections on scroll
        const sections = [
            document.getElementById('whySection'),
            document.getElementById('structureSection'),
            document.getElementById('features'),
            document.getElementById('howSection'),
            document.getElementById('exampleSection'),
            document.getElementById('betaSection'),
            document.getElementById('ctaSection'),
            document.getElementById('stravaSection')
        ];
        
        // Function to check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.85
            );
        }
        
        // Function to handle scroll animation
        function handleScrollAnimation() {
            sections.forEach(section => {
                if (section && isInViewport(section) && section.classList.contains('opacity-0')) {
                    section.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        }
        
        // Initial check
        handleScrollAnimation();
        
        // Listen for scroll
        window.addEventListener('scroll', handleScrollAnimation);
    });
</script>
@endsection