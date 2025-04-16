@extends('layouts.app')
@section('content')
<div class="welcome-container overflow-y-auto scrollbar-hidden">
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
                            <a href="{{ route('calendar') }}" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-running mr-2"></i> Create My Training Plan
                            </a>
                            <a href="#features" class="px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-search mr-2"></i> Discover Features
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
                    Many runners still plan their workouts in Excel spreadsheets.<br>
                    Zone 2 offers an interactive and structured alternative, designed for those who want to track their progress methodically.
                </p>
                
                <div class="bg-blue-900 bg-opacity-30 border border-blue-400 border-opacity-20 p-5 rounded-xl mb-8">
                    <p class="text-xl text-center font-semibold text-white">
                        Zone 2 isn't just a plan generator, it's your smart training companion that evolves with you.
                    </p>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-4">What Zone 2 Offers You:</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="benefit-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="benefit-icon mb-4 w-12 h-12 flex items-center justify-center bg-cyan-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-calendar-alt text-cyan-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">Clear Annual Planning</h4>
                        <p class="text-cyan-200">Visualize and organize your training year in just a few clicks</p>
                    </div>
                    
                    <div class="benefit-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="benefit-icon mb-4 w-12 h-12 flex items-center justify-center bg-orange-500 bg-opacity-25 rounded-full">
                            <i class="fab fa-strava text-orange-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">Automatic Strava Connection</h4>
                        <p class="text-cyan-200">Import your activities with one click and compare them to your plan</p>
                    </div>
                    
                    <div class="benefit-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="benefit-icon mb-4 w-12 h-12 flex items-center justify-center bg-teal-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-puzzle-piece text-teal-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">Coherent Block Structure</h4>
                        <p class="text-cyan-200">Organize your weeks based on your specific goals</p>
                    </div>
                    
                    <div class="benefit-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="benefit-icon mb-4 w-12 h-12 flex items-center justify-center bg-purple-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-chart-line text-purple-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">Performance Analysis</h4>
                        <p class="text-cyan-200">Track your progress and compare goals to results</p>
                    </div>
                    
                    <div class="benefit-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="benefit-icon mb-4 w-12 h-12 flex items-center justify-center bg-blue-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-pencil-alt text-blue-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">100% Customized Plans</h4>
                        <p class="text-cyan-200">Create and adapt your plan according to your specific needs</p>
                    </div>
                    
                    <div class="benefit-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="benefit-icon mb-4 w-12 h-12 flex items-center justify-center bg-red-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-brain text-red-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">Training Intelligence</h4>
                        <p class="text-cyan-200">Overload alerts and advice to optimize your progression</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Structure Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="structureSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
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
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
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
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="howSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                        <i class="fas fa-question text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">How It Works</h2>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-6">Your Training in 5 Steps</h3>
                
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="md:w-full">
                        <div class="steps-container relative">
                            <div class="steps-line absolute left-6 top-8 bottom-0 w-0.5 bg-cyan-500 z-0"></div>
                            
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

        <!-- Example Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="exampleSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                        <i class="fas fa-running text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">Real Example – Preparing for a Marathon in 16 Weeks</h2>
                </div>
                
                <div class="bg-blue-900 bg-opacity-30 border border-blue-400 border-opacity-20 p-5 rounded-xl mb-8">
                    <p class="text-lg italic text-white">
                        "I'm preparing for a marathon on October 6th. I've created my training plan in Zone 2 spanning 16 weeks."
                    </p>
                </div>
                
                <!-- Timeline -->
                <div class="timeline-container mb-10 relative overflow-hidden pt-5 pb-4">
                    <div class="h-1 bg-gray-700 absolute left-0 right-0 top-1/2 transform -translate-y-1/2"></div>
                    
                    <div class="flex justify-between relative pt-4">
                        <!-- Week 1 -->
                        <div class="timeline-item flex flex-col items-center relative z-10">
                            <div class="timeline-dot w-8 h-8 rounded-full bg-blue-600 mb-3 flex items-center justify-center text-white font-bold text-xs">1-3</div>
                            <div class="timeline-week text-xs font-bold text-white bg-blue-600 px-2 py-1 rounded mb-2">Week 1-3</div>
                            <div class="timeline-content max-w-[100px] text-center">
                                <div class="text-xs text-blue-300">Development</div>
                                <div class="text-xs text-white">Startup</div>
                            </div>
                        </div>
                        
                        <!-- Week 2 -->
                        <div class="timeline-item flex flex-col items-center relative z-10">
                            <div class="timeline-dot w-8 h-8 rounded-full bg-emerald-400 mb-3"></div>
                            <div class="timeline-week text-xs font-bold text-white bg-emerald-600 px-2 py-1 rounded mb-2">Week 4</div>
                            <div class="timeline-content max-w-[100px] text-center">
                                <div class="text-xs text-pink-300">Reduced</div>
                                <div class="text-xs text-white">Reduced Volume</div>
                            </div>
                        </div>
                        
                        <!-- Weeks 3-9 -->
                        <div class="timeline-item flex flex-col items-center relative z-10">
                            <div class="timeline-dot w-8 h-8 rounded-full bg-blue-600 mb-3 flex items-center justify-center text-white font-bold text-xs">5-9</div>
                            <div class="timeline-week text-xs font-bold text-white bg-blue-600 px-2 py-1 rounded mb-2">Weeks 5-9</div>
                            <div class="timeline-content max-w-[100px] text-center">
                                <div class="text-xs text-blue-300">Development</div>
                                <div class="text-xs text-white">Progressive Load</div>
                            </div>
                        </div>
                        
                        <!-- Weeks 10-13 -->
                        <div class="timeline-item flex flex-col items-center relative z-10">
                            <div class="timeline-dot w-8 h-8 rounded-full bg-amber-700 mb-3 flex items-center justify-center text-white font-bold text-xs">10-13</div>
                            <div class="timeline-week text-xs font-bold text-white bg-amber-600 px-2 py-1 rounded mb-2">Weeks 10-13</div>
                            <div class="timeline-content max-w-[100px] text-center">
                                <div class="text-xs text-amber-300">Maintenance</div>
                                <div class="text-xs text-white">Stable Volume</div>
                            </div>
                        </div>
                        
                        <!-- Weeks 14-15 -->
                        <div class="timeline-item flex flex-col items-center relative z-10">
                            <div class="timeline-dot w-8 h-8 rounded-full bg-fuchsia-600 mb-3 flex items-center justify-center text-white font-bold text-xs">14-15</div>
                            <div class="timeline-week text-xs font-bold text-white bg-fuchsia-600 px-2 py-1 rounded mb-2">Weeks 14-15</div>
                            <div class="timeline-content max-w-[100px] text-center">
                                <div class="text-xs text-fuchsia-300">Taper</div>
                                <div class="text-xs text-white">Reduction</div>
                            </div>
                        </div>
                        
                        <!-- Week 16 -->
                        <div class="timeline-item flex flex-col items-center relative z-10">
                            <div class="timeline-dot w-8 h-8 rounded-full bg-rose-600 mb-3"></div>
                            <div class="timeline-week text-xs font-bold text-white bg-rose-600 px-2 py-1 rounded mb-2">Week 16</div>
                            <div class="timeline-content max-w-[100px] text-center">
                                <div class="text-xs text-rose-300">Competition</div>
                                <div class="text-xs text-white">Marathon!</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row gap-8 mt-8">
                    <div class="md:w-1/2">
                        <h3 class="text-xl font-bold text-white mb-4">My Week Organization</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <div class="mt-1 w-5 h-5 rounded-full bg-rose-600 flex-shrink-0"></div>
                                <span class="text-white"><span class="font-semibold">Week 16:</span> Competition</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 w-5 h-5 rounded-full bg-fuchsia-600 flex-shrink-0"></div>
                                <span class="text-white"><span class="font-semibold">Weeks 14–15:</span> Taper – Progressive load reduction</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 w-5 h-5 rounded-full bg-amber-700 flex-shrink-0"></div>
                                <span class="text-white"><span class="font-semibold">Weeks 10–13:</span> Maintenance – Stable volume and controlled intensity</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 w-5 h-5 rounded-full bg-blue-600 flex-shrink-0"></div>
                                <span class="text-white"><span class="font-semibold">Weeks 3–9:</span> Development – Progressive increase in training load</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 w-5 h-5 rounded-full bg-pink-600 flex-shrink-0"></div>
                                <span class="text-white"><span class="font-semibold">Week 2:</span> Recovery – Active rest</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 w-5 h-5 rounded-full bg-blue-600 flex-shrink-0"></div>
                                <span class="text-white"><span class="font-semibold">Week 1:</span> Development – Gentle start</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="md:w-1/2">
                        <h3 class="text-xl font-bold text-white mb-4">My Method</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <div class="mt-1 text-cyan-400"><i class="fas fa-check-circle"></i></div>
                                <span class="text-white">Weekly goals defined <span class="font-semibold">in time</span></span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 text-cyan-400"><i class="fas fa-check-circle"></i></div>
                                <span class="text-white"><span class="font-semibold">3 to 5 sessions</span> per week based on my availability</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 text-cyan-400"><i class="fas fa-check-circle"></i></div>
                                <span class="text-white">Weekly review to adjust training load</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <div class="mt-1 text-cyan-400"><i class="fas fa-check-circle"></i></div>
                                <span class="text-white">Zone 2 alerts me if I increase too quickly (>10%)</span>
                            </li>
                        </ul>
                        
                        <div class="bg-blue-900 bg-opacity-30 border border-blue-400 border-opacity-20 p-5 rounded-xl mt-6">
                            <p class="text-lg italic text-white">
                                "Thanks to Zone 2, I maintain a clear vision of my journey and approach the race with confidence."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Beta Notice Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="betaSection">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-amber-600 flex items-center justify-center">
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
                    <a href="{{ route('calendar') }}" class="px-8 py-4 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center text-lg">
                        <i class="fas fa-check-circle mr-2"></i> Create My Plan Now
                    </a>
                    {{-- <button id="openDemoModal" class="px-8 py-4 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center text-lg">
                        <i class="fas fa-play-circle mr-2"></i> See Interface Demo
                    </button> --}}
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
    
    .timeline-dot {
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
    }
    
    @media (max-width: 768px) {
        .timeline-content {
            max-width: 60px;
        }
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
            document.getElementById('ctaSection')
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