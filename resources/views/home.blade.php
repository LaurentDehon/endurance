@extends('layouts.app')

@section('title', __('home.meta.title'))
@section('meta_description', __('home.meta.description'))
@section('meta_keywords', __('home.meta.keywords'))
@section('og_title', __('home.meta.og_title'))
@section('og_description', __('home.meta.og_description'))

@section('content')
<div class="welcome-container overflow-y-auto w-full">
    <div class="mx-auto px-4 py-8 max-w-7xl">
        <!-- Hero Section -->
        <div class="hero-section bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg mb-12 p-8">
            <div class="container mx-auto">
                <div class="flex flex-col md:flex-row items-center gap-12">
                    <div class="md:w-full">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white">{{ __('home.hero.title') }}</h1>
                        <p class="text-xl text-cyan-200 mb-6">
                            {{ __('home.hero.description') }}
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="@auth {{ route('calendar') }} @else {{ route('login') }} @endauth" class="px-6 py-3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-running mr-2"></i> @auth {{ __('home.hero.create_plan') }} @else {{ __('home.hero.sign_in') }} @endauth
                            </a>
                            <a href="#features" class="px-6 py-3 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-search mr-2"></i> {{ __('home.hero.discover') }}
                            </a>
                            <a href="{{ route('help') }}" class="px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center">
                                <i class="fas fa-question-circle mr-2"></i> {{ __('home.hero.help') }}
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
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-8 shadow-lg">
                <h2 class="text-3xl font-bold text-white mb-6">{{ __('home.why_section.title') }}</h2>
                <p class="text-lg text-cyan-200 mb-6">
                    {{ __('home.why_section.description') }}
                </p>
                
                <div class="bg-blue-900 bg-opacity-40 p-5 rounded-xl mb-8">
                    <p class="text-xl text-center font-semibold text-white">
                        {{ __('home.why_section.tagline') }}
                    </p>
                </div>   
            </div>
        </section>

        <!-- Structure Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="structureSection">
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-8 shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-cube text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">{{ __('home.structure_section.title') }}</h2>
                </div>
                
                <div class="flex flex-col md:flex-row gap-8">
                    <div class="md:w-full">
                        <h3 class="text-2xl font-bold text-white mb-4">{{ __('home.structure_section.subtitle') }}</h3>
                        <p class="text-lg text-cyan-200 mb-4">
                            {{ __('home.structure_section.description_1') }}
                        </p>
                        <p class="text-lg text-cyan-200 mb-6">
                            {{ __('home.structure_section.description_2') }}
                        </p>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-white bg-opacity-10 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-pink-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-bed text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">{{ __('home.structure_section.week_types.recovery.title') }}</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    {{ __('home.structure_section.week_types.recovery.description') }}
                                </p>
                            </div>

                            <div class="bg-white bg-opacity-10 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-arrow-up text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">{{ __('home.structure_section.week_types.development.title') }}</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    {{ __('home.structure_section.week_types.development.description') }}
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-amber-700 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-equals text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">{{ __('home.structure_section.week_types.maintain.title') }}</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    {{ __('home.structure_section.week_types.maintain.description') }}
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-emerald-400 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-arrow-down text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">{{ __('home.structure_section.week_types.reduced.title') }}</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    {{ __('home.structure_section.week_types.reduced.description') }}
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-fuchsia-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-compress-alt text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">{{ __('home.structure_section.week_types.taper.title') }}</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    {{ __('home.structure_section.week_types.taper.description') }}
                                </p>
                            </div>
                            
                            <div class="bg-white bg-opacity-10 shadow-sm rounded-xl p-4">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-rose-600 rounded-full flex-shrink-0 flex items-center justify-center">
                                        <i class="fas fa-flag-checkered text-white"></i>
                                    </div>
                                    <h4 class="font-medium text-white">{{ __('home.structure_section.week_types.race.title') }}</h4>
                                </div>
                                <p class="text-sm text-cyan-200">
                                    {{ __('home.structure_section.week_types.race.description') }}
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
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-8 shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-tools text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">{{ __('home.features_section.title') }}</h2>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-6">{{ __('home.features_section.subtitle') }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-cyan-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-calendar-alt text-cyan-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">{{ __('home.features_section.features.calendar.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.calendar.description') }}</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-yellow-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-bullseye text-yellow-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">{{ __('home.features_section.features.goals.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.goals.description') }}</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-orange-500 bg-opacity-25 rounded-full">
                            <i class="fab fa-strava text-orange-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">{{ __('home.features_section.features.strava.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.strava.description') }}</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-purple-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-chart-bar text-purple-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">{{ __('home.features_section.features.dashboards.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.dashboards.description') }}</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-teal-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-dumbbell text-teal-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">{{ __('home.features_section.features.week_types.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.week_types.description') }}</p>
                    </div>
                    
                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-6 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-14 h-14 flex items-center justify-center bg-red-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-exclamation-triangle text-red-400 text-2xl"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-white mb-2">{{ __('home.features_section.features.overload_alert.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.overload_alert.description') }}</p>
                    </div>

                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-12 h-12 flex items-center justify-center bg-indigo-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-puzzle-piece text-indigo-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.features_section.features.block_structure.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.block_structure.description') }}</p>
                    </div>

                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-12 h-12 flex items-center justify-center bg-blue-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-pencil-alt text-blue-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.features_section.features.custom_plans.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.custom_plans.description') }}</p>
                    </div>

                    <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-5 hover:transform hover:scale-105 transition-all duration-300">
                        <div class="feature-icon mb-4 w-12 h-12 flex items-center justify-center bg-pink-500 bg-opacity-25 rounded-full">
                            <i class="fas fa-eye text-pink-400 text-xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.features_section.features.annual_planning.title') }}</h4>
                        <p class="text-cyan-200">{{ __('home.features_section.features.annual_planning.description') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="howSection">
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-8 shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-question text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">{{ __('home.how_section.title') }}</h2>
                </div>
                
                <h3 class="text-2xl font-bold text-white mb-6">{{ __('home.how_section.subtitle') }}</h3>
                
                <div class="flex flex-col md:flex-row gap-8 items-center">
                    <div class="md:w-full">
                        <div class="steps-container relative">
                            <div class="steps-line absolute left-6 top-8 bottom-11 w-0.5 bg-cyan-500 z-0"></div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">1</div>
                                <div class="step-content bg-white bg-opacity-10 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.how_section.steps.step_1.title') }}</h4>
                                    <p class="text-cyan-200">{{ __('home.how_section.steps.step_1.description') }}</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">2</div>
                                <div class="step-content bg-white bg-opacity-10 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.how_section.steps.step_2.title') }}</h4>
                                    <p class="text-cyan-200">{{ __('home.how_section.steps.step_2.description') }}</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">3</div>
                                <div class="step-content bg-white bg-opacity-10 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.how_section.steps.step_3.title') }}</h4>
                                    <p class="text-cyan-200">{{ __('home.how_section.steps.step_3.description') }}</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4 mb-8">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">4</div>
                                <div class="step-content bg-white bg-opacity-10 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.how_section.steps.step_4.title') }}</h4>
                                    <p class="text-cyan-200">{{ __('home.how_section.steps.step_4.description') }}</p>
                                </div>
                            </div>
                            
                            <div class="step-item relative z-10 flex items-start gap-4">
                                <div class="step-number w-12 h-12 rounded-full bg-cyan-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">5</div>
                                <div class="step-content bg-white bg-opacity-10 shadow-sm rounded-xl p-4 flex-grow">
                                    <h4 class="text-lg font-semibold text-white mb-2">{{ __('home.how_section.steps.step_5.title') }}</h4>
                                    <p class="text-cyan-200">{{ __('home.how_section.steps.step_5.description') }}</p>
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
        <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-8 shadow-lg mb-10">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-route text-xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-white">{{ __('home.training_blocks.title') }}</h2>
            </div>
            
            <div class="bg-blue-900 bg-opacity-40 p-5 rounded-xl mb-8">
                <p class="text-lg italic text-white">
                    "{{ __('home.training_blocks.tagline') }}"
                </p>
            </div>
            
            <div class="space-y-6">
                <!-- Weeks 1-3: Development -->
                <div class="bg-blue-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.development_1.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-arrow-up text-blue-300 mr-2"></i> {{ __('home.training_blocks.blocks.development_1.title') }}
                        </h3>
                    </div>
                    <p class="text-blue-200 mb-3">{{ __('home.training_blocks.blocks.development_1.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-chart-line mr-2"></i> {{ __('home.training_blocks.blocks.development_1.points.0') }}</li>
                        <li><i class="fas fa-heartbeat mr-2"></i> {{ __('home.training_blocks.blocks.development_1.points.1') }}</li>
                        <li><i class="fas fa-clock mr-2"></i> {{ __('home.training_blocks.blocks.development_1.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Week 4: Reduced -->
                <div class="bg-emerald-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-emerald-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.reduced_1.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-arrow-down text-emerald-300 mr-2"></i> {{ __('home.training_blocks.blocks.reduced_1.title') }}
                        </h3>
                    </div>
                    <p class="text-emerald-200 mb-3">{{ __('home.training_blocks.blocks.reduced_1.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-tachometer-alt mr-2"></i> {{ __('home.training_blocks.blocks.reduced_1.points.0') }}</li>
                        <li><i class="fas fa-walking mr-2"></i> {{ __('home.training_blocks.blocks.reduced_1.points.1') }}</li>
                        <li><i class="fas fa-bed mr-2"></i> {{ __('home.training_blocks.blocks.reduced_1.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Weeks 5-7: Development -->
                <div class="bg-blue-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.development_2.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-arrow-up text-blue-300 mr-2"></i> {{ __('home.training_blocks.blocks.development_2.title') }}
                        </h3>
                    </div>
                    <p class="text-blue-200 mb-3">{{ __('home.training_blocks.blocks.development_2.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-fire mr-2"></i> {{ __('home.training_blocks.blocks.development_2.points.0') }}</li>
                        <li><i class="fas fa-road mr-2"></i> {{ __('home.training_blocks.blocks.development_2.points.1') }}</li>
                        <li><i class="fas fa-chart-bar mr-2"></i> {{ __('home.training_blocks.blocks.development_2.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Week 8: Reduced -->
                <div class="bg-emerald-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-emerald-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.reduced_2.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-arrow-down text-emerald-300 mr-2"></i> {{ __('home.training_blocks.blocks.reduced_2.title') }}
                        </h3>
                    </div>
                    <p class="text-emerald-200 mb-3">{{ __('home.training_blocks.blocks.reduced_2.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-battery-half mr-2"></i> {{ __('home.training_blocks.blocks.reduced_2.points.0') }}</li>
                        <li><i class="fas fa-sync mr-2"></i> {{ __('home.training_blocks.blocks.reduced_2.points.1') }}</li>
                        <li><i class="fas fa-battery-three-quarters mr-2"></i> {{ __('home.training_blocks.blocks.reduced_2.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Weeks 9-10: Development -->
                <div class="bg-blue-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.development_3.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-arrow-up text-blue-300 mr-2"></i> {{ __('home.training_blocks.blocks.development_3.title') }}
                        </h3>
                    </div>
                    <p class="text-blue-200 mb-3">{{ __('home.training_blocks.blocks.development_3.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-mountain mr-2"></i> {{ __('home.training_blocks.blocks.development_3.points.0') }}</li>
                        <li><i class="fas fa-tachometer-alt mr-2"></i> {{ __('home.training_blocks.blocks.development_3.points.1') }}</li>
                        <li><i class="fas fa-brain mr-2"></i> {{ __('home.training_blocks.blocks.development_3.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Week 11: Reduced -->
                <div class="bg-emerald-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-emerald-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.reduced_3.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-arrow-down text-emerald-300 mr-2"></i> {{ __('home.training_blocks.blocks.reduced_3.title') }}
                        </h3>
                    </div>
                    <p class="text-emerald-200 mb-3">{{ __('home.training_blocks.blocks.reduced_3.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-battery-quarter mr-2"></i> {{ __('home.training_blocks.blocks.reduced_3.points.0') }}</li>
                        <li><i class="fas fa-redo mr-2"></i> {{ __('home.training_blocks.blocks.reduced_3.points.1') }}</li>
                        <li><i class="fas fa-search mr-2"></i> {{ __('home.training_blocks.blocks.reduced_3.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Weeks 12-13: Maintain -->
                <div class="bg-amber-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-amber-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.maintain.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-equals text-amber-300 mr-2"></i> {{ __('home.training_blocks.blocks.maintain.title') }}
                        </h3>
                    </div>
                    <p class="text-amber-200 mb-3">{{ __('home.training_blocks.blocks.maintain.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-balance-scale mr-2"></i> {{ __('home.training_blocks.blocks.maintain.points.0') }}</li>
                        <li><i class="fas fa-stopwatch mr-2"></i> {{ __('home.training_blocks.blocks.maintain.points.1') }}</li>
                        <li><i class="fas fa-chess mr-2"></i> {{ __('home.training_blocks.blocks.maintain.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Weeks 14-15: Taper -->
                <div class="bg-fuchsia-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-fuchsia-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.taper.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-compress-alt text-fuchsia-300 mr-2"></i> {{ __('home.training_blocks.blocks.taper.title') }}
                        </h3>
                    </div>
                    <p class="text-fuchsia-200 mb-3">{{ __('home.training_blocks.blocks.taper.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-sort-amount-down mr-2"></i> {{ __('home.training_blocks.blocks.taper.points.0') }}</li>
                        <li><i class="fas fa-bolt mr-2"></i> {{ __('home.training_blocks.blocks.taper.points.1') }}</li>
                        <li><i class="fas fa-battery-full mr-2"></i> {{ __('home.training_blocks.blocks.taper.points.2') }}</li>
                    </ul>
                </div>
                
                <!-- Week 16: Race -->
                <div class="bg-rose-600 bg-opacity-20 p-5 rounded-xl shadow-lg max-w-[600px] mx-auto">
                    <div class="flex flex-col lg:flex-row lg:items-center">
                        <div class="mb-2 lg:mb-0 lg:mr-4">
                            <span class="px-3 py-1 bg-rose-600 text-white text-sm rounded-full inline-block">{{ __('home.training_blocks.blocks.race.weeks') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 lg:mb-0">
                            <i class="fas fa-flag-checkered text-rose-300 mr-2"></i> {{ __('home.training_blocks.blocks.race.title') }}
                        </h3>
                    </div>
                    <p class="text-rose-200 mb-3">{{ __('home.training_blocks.blocks.race.description') }}</p>
                    <ul class="text-white space-y-2 ml-6">
                        <li><i class="fas fa-feather mr-2"></i> {{ __('home.training_blocks.blocks.race.points.0') }}</li>
                        <li><i class="fas fa-clipboard-check mr-2"></i> {{ __('home.training_blocks.blocks.race.points.1') }}</li>
                        <li><i class="fas fa-medal mr-2"></i> {{ __('home.training_blocks.blocks.race.points.2') }}</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Beta Notice Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="betaSection">
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-8 shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-amber-600 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-flask text-xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white">{{ __('home.beta_section.title') }}</h2>
                </div>
                
                <div class="flex items-center gap-6 bg-amber-900 bg-opacity-30 p-5 rounded-xl mb-8">
                    <div class="text-amber-400 text-4xl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <p class="text-lg text-white mb-2">{{ __('home.beta_section.message_1') }}</p>
                        <p class="text-white">{{ __('home.beta_section.message_2') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="ctaSection">
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-8 shadow-lg text-center">
                <h2 class="text-3xl font-bold text-white mb-6">{{ __('home.cta_section.title') }}</h2>
                
                <div class="flex flex-wrap justify-center gap-4 mb-8">
                    <a href="@auth {{ route('calendar') }} @else {{ route('login') }} @endauth" class="px-8 py-4 bg-cyan-600 hover:bg-cyan-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center text-lg">
                        <i class="fas fa-check-circle mr-2"></i> @auth {{ __('home.cta_section.create_now') }} @else {{ __('home.cta_section.sign_in') }} @endauth
                    </a>
                    {{-- <button id="openDemoModal" class="px-8 py-4 bg-amber-600 hover:bg-amber-500 text-white font-bold rounded-full transition-all duration-300 shadow-lg hover:shadow-2xl flex items-center text-lg">
                        <i class="fas fa-play-circle mr-2"></i> See Interface Demo
                    </button> --}}
                </div>
                
                @guest
                <div class="mt-4 flex justify-center gap-4">
                    <a href="{{ route('login') }}" class="text-cyan-300 hover:underline">{{ __('home.cta_section.log_in') }}</a>
                    <span class="text-white">•</span>
                    <a href="{{ route('register') }}" class="text-cyan-300 hover:underline">{{ __('home.cta_section.create_account') }}</a>
                </div>
                @endguest
            </div>
        </section>

        <!-- Logos Section -->
        <section class="mb-16 opacity-0 transform translate-y-10 transition-all duration-1000 ease-out" id="stravaSection">
            <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg flex justify-center items-center">
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