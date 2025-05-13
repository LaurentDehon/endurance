@extends('layouts.app')
@section('content')
<div class="help-container mx-auto p-4 md:p-8 max-w-7xl">
    <!-- Header Section -->
    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg mb-8 py-6">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ __('help.faq_title') }}</h1>
            <p class="text-lg md:text-xl text-cyan-200 max-w-2xl mx-auto mb-4">
                {{ __('help.faq_subtitle') }}
            </p>
            <div class="flex flex-wrap justify-center gap-4 mt-6">
                <a href="#general" class="px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-question-circle"></i> {{ __('help.general_questions') }}
                </a>
                <a href="{{ route('calendar') }}" class="px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2">
                    <i class="fas fa-calendar"></i> {{ __('help.go_to_calendar') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Pills -->
    <div class="mb-8 p-3 bg-white bg-opacity-10 shadow-sm rounded-lg">
        <div class="flex flex-wrap justify-center gap-2">
            <a href="#general" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-info-circle"></i> {{ __('help.general') }}
            </a>
            <a href="#plan" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-tasks"></i> {{ __('help.planning') }}
            </a>
            <a href="#metrics" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-chart-bar"></i> {{ __('help.metrics') }}
            </a>
            <a href="#strava" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fab fa-strava"></i> {{ __('help.strava') }}
            </a>
            <a href="#weeks" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-calendar-week"></i> {{ __('help.weeks') }}
            </a>
            <a href="#workouts" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-running"></i> {{ __('help.workouts') }}
            </a>
            <a href="#tips" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-lightbulb"></i> {{ __('help.tips') }}
            </a>
            <a href="#data" class="whitespace-nowrap px-4 py-2 text-white bg-cyan-600 hover:bg-cyan-500 rounded-lg transition-all duration-300 flex items-center gap-2 text-sm">
                <i class="fas fa-database"></i> {{ __('help.data') }}
            </a>
        </div>
    </div>

    <!-- Features Quick Access -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
        <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-orange-500 bg-opacity-25 rounded-full">
                <i class="fab fa-strava text-orange-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">{{ __('help.strava_sync') }}</h3>
        </div>
        
        <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-teal-500 bg-opacity-25 rounded-full">
                <i class="fas fa-calendar text-teal-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">{{ __('help.weekly_planning') }}</h3>
        </div>
        
        <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-purple-500 bg-opacity-25 rounded-full">
                <i class="fas fa-chart-line text-purple-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">{{ __('help.performance_tracking') }}</h3>
        </div>
        
        <div class="feature-card bg-white bg-opacity-10 shadow-sm backdrop-blur-lg rounded-xl p-4 text-center hover:transform hover:scale-105 transition-all duration-300 flex flex-col items-center">
            <div class="feature-icon mb-3 w-12 h-12 flex items-center justify-center bg-blue-500 bg-opacity-25 rounded-full">
                <i class="fas fa-lightbulb text-blue-400"></i>
            </div>
            <h3 class="text-sm font-medium text-white">{{ __('help.pro_tips') }}</h3>
        </div>
    </div>

    <!-- FAQ Accordion -->
    <div class="space-y-6">
        <!-- General Section -->
        <section id="general" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-info-circle text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.general_questions_title') }}</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.what_is_zone2') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.what_is_zone2_answer_1') }}</p>
                            <p class="mt-2">{{ __('help.what_is_zone2_answer_2') }}</p>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.main_features') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ul class="list-disc pl-5 space-y-2">
                                @foreach(__('help.main_features_list') as $feature)
                                <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.is_zone2_free') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.is_zone2_free_answer') }}</p>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.works_on_mobile') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.works_on_mobile_answer') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Planning Section -->
        <section id="plan" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-tasks text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.training_planning_title') }}</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.create_training_plan') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.create_training_plan') }}:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                @foreach(__('help.create_training_plan_steps') as $step)
                                <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                            {{-- <div class="mt-4">
                                <a href="#" class="text-cyan-400 underline">Watch the tutorial video</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.different_workout_types') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.different_workout_types_answer') }}</p>
                            <ul class="space-y-2 mt-2">
                                @foreach(__('help.different_workout_types_list') as $workout)
                                <li>{!! $workout !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.modify_session') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.modify_session') }}:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                @foreach(__('help.modify_session_steps') as $step)
                                <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                            <p class="mt-2">{!! __('help.modify_session_drag_drop') !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Metrics Section -->
        <section id="metrics" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-chart-bar text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.performance_metrics_title') }}</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center bg-white bg-opacity-10 shadow-sm p-5 rounded-xl">
                    <i class="fas fa-route text-blue-400 text-4xl py-3"></i>                    
                    <h3 class="text-lg font-semibold text-white">{{ __('help.distance') }}</h3>
                    <p class="text-sm text-cyan-200 mt-2">{{ __('help.distance_desc') }}</p>
                </div>
                
                <div class="text-center bg-white bg-opacity-10 shadow-sm p-5 rounded-xl">
                    <i class="fas fa-stopwatch text-green-400 text-4xl py-3"></i>                    
                    <h3 class="text-lg font-semibold text-white">{{ __('help.duration') }}</h3>
                    <p class="text-sm text-cyan-200 mt-2">{{ __('help.duration_desc') }}</p>
                </div>

                <div class="text-center bg-white bg-opacity-10 shadow-sm p-5 rounded-xl">
                    <i class="fas fa-mountain text-red-400 text-4xl py-3"></i>
                    <h3 class="text-lg font-semibold text-white">{{ __('help.elevation') }}</h3>
                    <p class="text-sm text-cyan-200 mt-2">{{ __('help.elevation_desc') }}</p>
                </div>                
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.interpret_colors') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.interpret_colors_answer') }}</p>
                            <ul class="space-y-2 mt-2">
                                @foreach(__('help.interpret_colors_list') as $color)
                                <li>{!! $color !!}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2">{!! __('help.interpret_colors_format') !!}</p>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.see_progress') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.see_progress') }}:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                @foreach(__('help.see_progress_steps') as $step)
                                <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Strava Section -->
        <section id="strava" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fab fa-strava text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.strava_sync_title') }}</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.connect_strava') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ol class="list-decimal pl-5 space-y-2">
                                @foreach(__('help.connect_strava_steps') as $step)
                                <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.strava_not_syncing') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.strava_not_syncing') }}:</p>
                            <ol class="list-decimal pl-5 space-y-2 mt-2">
                                @foreach(__('help.strava_not_syncing_steps') as $step)
                                <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.use_without_strava') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.use_without_strava_answer') }}</p>                            
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Week Types Section -->
        <section id="weeks" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.week_types_title') }}</h2>
            </div>
            
            <div class="mb-6">
                <div class="bg-red-900 bg-opacity-20 p-5 rounded-xl mb-4">
                    <h3 class="font-semibold text-white mb-3">{{ __('help.available_week_types') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-blue-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.development') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.development_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-amber-700 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.maintain') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.maintain_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-emerald-400 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.reduced') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.reduced_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-pink-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.recovery') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.recovery_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-fuchsia-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.tapering') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.tapering_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-rose-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.race') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.race_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.choose_week_type') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.choose_week_type_answer') }}</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                @foreach(__('help.choose_week_type_list') as $item)
                                <li>{!! $item !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.change_week_type') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ol class="list-decimal pl-5 space-y-2">
                                @foreach(__('help.change_week_type_steps') as $step)
                                <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Workout Types Section -->
        <section id="workouts" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-running text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.workout_types_title') }}</h2>
            </div>
            
            <div class="mb-6">
                <div class="bg-blue-900 bg-opacity-20 p-5 rounded-xl mb-4">
                    <h3 class="font-semibold text-white mb-3">{{ __('help.available_workout_types') }}</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-blue-500 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.easy_run') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.easy_run_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-green-500 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.long_run') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.long_run_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-stone-500 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.recovery_run') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.recovery_run_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-pink-500 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.fartlek') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.fartlek_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-slate-800 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.tempo_run') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.tempo_run_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-purple-500 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.hill_repeats') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.hill_repeats_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-red-500 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.intervals') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.intervals_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-cyan-600 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.back_to_back') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.back_to_back_desc') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-2">
                            <div class="mt-1 w-4 h-4 rounded-full bg-red-700 flex-shrink-0"></div>
                            <div>
                                <span class="font-medium text-white">{{ __('help.race') }}</span>
                                <p class="text-sm text-cyan-200">{{ __('help.race_desc') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.choose_workout_type') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.choose_workout_type_answer') }}</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                @foreach(__('help.choose_workout_type_list') as $item)
                                <li>{!! $item !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.create_specific_workout') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ol class="list-decimal pl-5 space-y-2">
                                @foreach(__('help.create_specific_workout_steps') as $step)
                                <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                            <p class="mt-2">{{ __('help.create_specific_workout_note') }}</p>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.letters_meaning') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.letters_meaning_answer') }}</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                @foreach(__('help.letters_meaning_list') as $letter => $meaning)
                                <li>{!! $meaning !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pro Tips Section -->
        <section id="tips" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-lightbulb text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.expert_tips_title') }}</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.structure_training') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.structure_training_answer') }}</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                @foreach(__('help.structure_training_list') as $tip)
                                <li>{{ $tip }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.planning_pitfalls') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <ul class="list-disc pl-5 space-y-2">
                                @foreach(__('help.planning_pitfalls_list') as $pitfall)
                                <li>{{ $pitfall }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Data Management Section -->
        <section id="data" class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center">
                    <i class="fas fa-database text-xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('help.data_management_title') }}</h2>
            </div>
            
            <div class="space-y-4">
                <div x-data="{ open: false }" class="faq-item bg-white bg-opacity-5 rounded-lg overflow-hidden">
                    <button @click="open = !open" class="w-full p-4 text-left flex justify-between items-center text-white font-medium">
                        <span>{{ __('help.delete_data') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.delete_data_answer') }}</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                @foreach(__('help.delete_data_list') as $item)
                                <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            <div class="mt-4 bg-red-900 bg-opacity-20 p-3 rounded-lg">
                                <p class="flex items-center gap-2">
                                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                                    <span>{{ __('help.delete_warning') }}</span>
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
                        <span>{{ __('help.data_secure') }}</span>
                        <i class="fas transition-transform" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>
                    <div x-show="open" x-collapse>
                        <div class="p-4 pt-0 text-cyan-200">
                            <p>{{ __('help.data_secure_answer') }}</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                @foreach(__('help.data_secure_list') as $item)
                                <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            <p class="mt-2">{!! __('help.see_privacy_policy') !!}</p>
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