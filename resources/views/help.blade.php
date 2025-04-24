@extends('layouts.app')
@section('content')
<div class="help-container mx-auto p-4 md:p-8 max-w-7xl">
    <!-- Header Section -->
    <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl shadow-lg mb-8 py-6 border">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">FAQ - Frequently Asked Questions</h1>
            <p class="text-lg md:text-xl text-cyan-200 max-w-2xl mx-auto mb-4">
                All the answers to your questions about Zone 2
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <a href="#general" class="px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-question-circle"></i> General Questions
                </a>
                <a href="{{ route('calendar') }}" class="px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar"></i> Go to Calendar
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Pills -->
    <div class="mb-8 p-3 bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm rounded-lg border">
        <div class="flex flex-wrap justify-center gap-2">
            <a href="#general" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-info-circle"></i> General
            </a>
            <a href="#plan" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-tasks"></i> Planning
            </a>
            <a href="#metrics" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-chart-bar"></i> Metrics
            </a>
            <a href="#strava" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fab fa-strava"></i> Strava
            </a>
            <a href="#weeks" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-calendar-week"></i> Weeks
            </a>
            <a href="#tips" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-lightbulb"></i> Tips
            </a>
            <a href="#data" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-database"></i> Data
            </a>
        </div>
    </div>

    <!-- Features Quick Access -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-orange-500 bg-opacity-25 rounded-full">
                <i class="fab fa-strava text-orange-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">Strava Sync</h3>
        </div>
        
        <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-teal-500 bg-opacity-25 rounded-full">
                <i class="fas fa-calendar text-teal-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">Weekly Planning</h3>
        </div>
        
        <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-purple-500 bg-opacity-25 rounded-full">
                <i class="fas fa-chart-line text-purple-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">Performance Tracking</h3>
        </div>
        
        <div class="feature-card bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm border backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-blue-500 bg-opacity-25 rounded-full">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">Pro Tips</h3>
        </div>
    </div>

    <!-- FAQ Accordion -->
    <div class="space-y-6">
        <!-- General Section -->
        <section id="general" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">General Questions</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>What is Zone 2?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>Zone 2 is an interactive training calendar that combines your actual sports activities (synced from Strava) with your planned workout sessions.</p>
                            <p class="mt-2">It's a tool designed for athletes who want to structure their progression and methodically track their performance.</p>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>What are the main features of Zone 2?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Visualization of weekly/monthly/yearly training loads</li>
                                <li>Comparison between planned and actual performances</li>
                                <li>Organization of training cycles with different week types</li>
                                <li>Drag and drop workout sessions</li>
                                <li>Track progress with visual indicators</li>
                                <li>Adapt your schedule based on your goals</li>
                                <li>Strava synchronization</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>Is Zone 2 free?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>Zone 2 is currently in beta and free to access.</p>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>Does Zone 2 work on mobile?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>Yes, Zone 2 is designed to be fully responsive and work on all devices, from mobile phones to desktop computers. However some functionalities may be limited on smaller screens at the moment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Planning Section -->
        <section id="plan" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">Training Planning</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How do I create my training plan?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>To create your training plan in Zone 2:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                <li>Go to the calendar</li>
                                <li>Set your main goal (marathon, trail running, etc.)</li>
                                <li>Structure your training weeks in appropriate blocks</li>
                                <li>Plan your weekly sessions with details on type, distance, duration, and elevation</li>
                                <li>Sync with Strava or manually enter your completed activities</li>
                            </ol>
                            {{-- <div class="mt-4">
                                <a href="#" class="text-cyan-400 underline">Watch the tutorial video</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>Can I plan different types of workouts?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>Yes, Zone 2 allows you to plan various types of workouts:</p>
                            <ul class="space-y-2 mt-2">
                                <li><span class="font-medium">Easy Run:</span> Low intensity to develop aerobic endurance without excessive fatigue</li>
                                <li><span class="font-medium">Recovery Run:</span> Very light run after intense effort to promote muscle recovery</li>
                                <li><span class="font-medium">Intervals:</span> Intense periods followed by rest to improve speed and cardiovascular condition</li>
                                <li><span class="font-medium">Long Run:</span> Sustained run at moderate pace to develop endurance</li>
                                <li><span class="font-medium">Fartlek:</span> Mix of fast and slow running with spontaneous speed variations</li>
                                <li><span class="font-medium">Tempo:</span> Sustained, moderate effort to improve lactate threshold</li>
                                <li><span class="font-medium">Hill Repeats:</span> Intense uphill sprints followed by recovery jogs</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How do I modify a planned session?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>To modify a session:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                <li>Click on the session in the calendar</li>
                                <li>Edit the details in the form that opens</li>
                                <li>Save your changes</li>
                            </ol>
                            <p class="mt-2">You can also move a session by drag-and-drop directly in the calendar. You can also copy a session by holding the <strong>Ctrl</strong> key (or <strong>Cmd</strong> on Mac) while dragging it to another date.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Metrics Section -->
        <section id="metrics" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">Performance Metrics</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm p-5 rounded-xl">
                    <i class="fas fa-route text-blue-400 text-4xl py-3"></i>                    
                    <h3 class="text-lg font-semibold text-white">Distance</h3>
                    <p class="text-sm text-cyan-200 mt-2">Complete tracking of distance covered compared to your goals</p>
                </div>
                
                <div class="text-center bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm p-5 rounded-xl">
                    <i class="fas fa-stopwatch text-green-400 text-4xl py-3"></i>                    
                    <h3 class="text-lg font-semibold text-white">Duration</h3>
                    <p class="text-sm text-cyan-200 mt-2">Workout time management and comparison with planned sessions</p>
                </div>

                <div class="text-center bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm p-5 rounded-xl">
                    <i class="fas fa-mountain text-red-400 text-4xl py-3"></i>
                    <h3 class="text-lg font-semibold text-white">Elevation</h3>
                    <p class="text-sm text-cyan-200 mt-2">Precise analysis of climbs and positive elevation gain</p>
                </div>                
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How do I interpret the metric colors?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>The color code for metrics is as follows:</p>
                            <ul class="space-y-2 mt-2">
                                <li><span class="font-semibold text-blue-500">Blue</span> = Distance</li>
                                <li><span class="font-semibold text-red-500">Red</span> = Elevation</li>
                                <li><span class="font-semibold text-green-500">Green</span> = Duration</li>
                            </ul>
                            <p class="mt-2">Display format: <span class="font-semibold">Actual / Planned</span></p>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How can I see my progress over time? (not yet implemented)</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>To see your progress over time:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                <li>Go to the dashboard</li>
                                <li>Check the charts showing the progression of your metrics (distance, elevation, time)</li>
                                <li>Use filters to display different periods (week, month, year)</li>
                                <li>Compare your actual performances with your planned goals</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Strava Section -->
        <section id="strava" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fab fa-strava text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">Strava Synchronization</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How do I connect my Strava account?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>By clicking on the dashboard, calendar or activities page, you will be prompted to connect your Strava account</li>
                                <li>Click on "Connect to Strava"</li>
                                <li>Authorize Zone 2 to access your Strava data</li>
                                <li>Once connected, you will be able to synchronize your activities from the calendar</li>
                                <li>Your activities will automatically appear on the days they were performed</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>What should I do if my Strava activities aren't syncing?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>If your activities aren't syncing:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                <li>Make sure you're properly connected to Strava</li>
                                <li>Click the "Sync" button in the calendar</li>
                                <li>If the problem persists, disconnect and reconnect your Strava account</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>Can I use Zone 2 without Strava?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>No, Zone 2 is designed to work with Strava data for optimal performance tracking.</p>                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Week Types Section -->
        <section id="weeks" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">Week Types</h2>
            </div>
            
            <div class="mb-6">
                <div class="bg-red-900 bg-opacity-20 border border-red-600 border-opacity-20 p-5 rounded-xl mb-4">
                    <h3 class="font-semibold text-white mb-3">Available Week Types</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-blue-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">Development</span>
                                <p class="text-sm text-cyan-200">High-load week designed to improve endurance, speed, or strength</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-amber-700 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">Maintenance</span>
                                <p class="text-sm text-cyan-200">Balanced week that maintains fitness without excessive stress</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-emerald-400 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">Reduced</span>
                                <p class="text-sm text-cyan-200">Week with reduced volume to prevent burnout and promote adaptation</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-pink-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">Recovery</span>
                                <p class="text-sm text-cyan-200">Low-intensity week focused on rest and active recovery</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-fuchsia-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">Tapering</span>
                                <p class="text-sm text-cyan-200">Progressive reduction of training volume before a competition</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-rose-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">Race</span>
                                <p class="text-sm text-cyan-200">Competition and recovery week</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How to choose the right week type?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>The choice of week type depends on your overall training plan and the phase you're in:</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                <li>Use <strong>Development</strong> weeks during intensive training periods</li>
                                <li>Alternate with <strong>Reduced</strong> weeks every 3-4 weeks to promote recovery</li>
                                <li>Use <strong>Maintenance</strong> weeks between intensive training blocks</li>
                                <li>Schedule a <strong>Recovery</strong> week after a competition or an intense training block</li>
                                <li>Include 1-3 <strong>Tapering</strong> weeks before an important competition</li>
                                <li>Mark your competitions as <strong>Race</strong> weeks</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How to change a week type?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ol class="list-decimal pl-5 space-y-2">
                                <li>Go to the calendar view</li>
                                <li>Click on the week header</li>
                                <li>Select the desired week type from the dropdown menu</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pro Tips Section -->
        <section id="tips" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-lightbulb text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">Expert Tips</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How to structure my training effectively?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>For an effective training structure:</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                <li>Follow the 10% rule (don't increase your volume by more than 10% per week)</li>
                                <li>Follow a cycle of 3-4 weeks of increasing load followed by a recovery week</li>
                                <li>Vary the types of training (endurance, speed, strength)</li>
                                <li>Include at least one complete rest day per week</li>
                                <li>Plan your intensive sessions after recovery days</li>
                                <li>Adapt your plan according to how you feel and your actual performances</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>What pitfalls should be avoided in planning?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ul class="list-disc pl-5 space-y-2">
                                <li>Avoid increasing volume or intensity too quickly</li>
                                <li>Don't neglect recovery weeks</li>
                                <li>Don't do multiple intense sessions consecutively</li>
                                <li>Don't ignore signs of excessive fatigue or injury</li>
                                <li>Don't copy another athlete's plan without adapting it to your level</li>
                                <li>Avoid planning sessions that are too specific too early in your preparation</li>
                                <li>Remember that consistency takes precedence over intensity for long-term progress</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Data Management Section -->
        <section id="data" class="section-card bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-database text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">Data Management</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>How to delete data?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>Use deletion options with caution:</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                <li>To delete an individual session, go to the session view and use the trash icon</li>
                                <li>To delete entire weeks/months/years, use the corresponding menu</li>
                                <li>To delete activities, go to the Activities menu and use the delete option</li>
                            </ul>
                            <div class="mt-4 bg-red-900 bg-opacity-20 border border-red-600 border-opacity-20 p-3 rounded-lg">
                                <p class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                                    <span>Warning: deleted data cannot be recovered</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>Can I export my data?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>Yes, you can export your data:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                <li>Go to Settings</li>
                                <li>Go to the "Data" section</li>
                                <li>Click on "Export my data"</li>
                                <li>Choose the format (CSV, JSON) and the period</li>
                            </ol>
                            <p class="mt-2">You can export all your data or select specific periods.</p>
                        </div>
                    </div>
                </div> --}}
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>Is my data secure?</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>Yes, your data is secure:</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                <li>All data is stored securely and confidentially</li>
                                <li>We only use your information to provide and improve the service</li>
                                <li>You maintain complete control over your data</li>
                                <li>You can request deletion of your data at any time</li>
                            </ul>
                            <p class="mt-2">For more information, see our <a href="#" class="text-cyan-400 underline">privacy policy</a>.</p>
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
    
    .faq-item {
        transition: all 0.3s ease;
    }
    
    .faq-item:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    @media (min-width: 1024px) {
        .two-columns {
            column-count: 2;
            column-gap: 2rem;
        }
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('collapse', () => ({
            open: false,
            
            toggle() {
                this.open = !this.open;
            }
        }));
    });
</script>
@endsection