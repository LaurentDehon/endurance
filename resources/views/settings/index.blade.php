@extends('layouts.app')
@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Fond d'écran fixe avec dégradé de couleur -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 -z-10"></div>
    
    <div class="max-w-3xl w-full space-y-8 bg-white bg-opacity-10 border-white border-opacity-20 border backdrop-blur-lg p-8 rounded-2xl shadow-lg">
        <!-- Header avec style amélioré -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white">
                <i class="fas fa-cogs mr-2 text-amber-300"></i>
                {{ __('settings.page_title') }}
            </h1>
            <p class="text-cyan-200 text-sm mt-2">{{ __('settings.page_description') }}</p>
        </div>

        <!-- Main Content -->
        <form method="POST" action="{{ route('settings.update') }}" class="space-y-10">
            @csrf
            @method('PATCH')
            
            <!-- Strava Connection -->
            <div class="space-y-6">
                <div class="border-b border-white border-opacity-20 pb-3">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-running mr-2 text-amber-300"></i>
                        {{ __('settings.strava.title') }}
                    </h2>
                </div>

                <div class="bg-slate-800 bg-opacity-50 p-6 rounded-xl">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <div class="flex items-center mb-2">
                                @if($user->strava_token && $user->strava_expires_at > now()->timestamp)
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                                    <span class="text-green-300 font-medium">{{ __('settings.strava.connected') }}</span>
                                @else
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                                    <span class="text-red-300 font-medium">{{ __('settings.strava.not_connected') }}</span>
                                @endif
                            </div>
                            <p class="text-slate-300 text-sm">
                                @if($user->strava_token && $user->strava_expires_at > now()->timestamp)
                                    {{ __('settings.strava.connected_description') }}
                                    <span class="block text-slate-400 text-xs mt-2">
                                        {{ __('settings.strava.connection_expires') }}: {{ \Carbon\Carbon::createFromTimestamp($user->strava_expires_at)->setTimezone($user->settings['timezone'] ?? config('app.timezone'))->format('Y-m-d H:i') }} ({{ $user->settings['timezone'] ?? 'local' }} time)
                                    </span>
                                @else
                                    {{ __('settings.strava.not_connected_description') }}
                                @endif
                            </p>
                        </div>
                        {{-- <div>
                            @if($user->strava_token && $user->strava_expires_at > now()->timestamp)
                                <a href="{{ route('strava.disconnect') }}" 
                                   class="flex items-center bg-orange-600 hover:bg-orange-500 text-white px-4 py-2 rounded-lg transition-all">
                                    <i class="fas fa-unlink mr-2"></i>
                                    {{ __('settings.strava.disconnect') }}
                                </a>
                            @else
                                <a href="{{ route('strava.redirect') }}" 
                                   class="flex items-center bg-orange-600 hover:bg-orange-500 text-white px-4 py-2 rounded-lg transition-all">
                                    <i class="fab fa-strava mr-2"></i>
                                    {{ __('settings.strava.connect') }}
                                </a>
                            @endif
                        </div> --}}
                    </div>
                    
                    <!-- Strava Settings -->
                    <div class="space-y-4">                        
                        <div class="flex items-center">
                            <input type="checkbox" id="auto_renew_token" name="auto_renew_token" value="1" 
                                   class="rounded bg-slate-700 border-slate-600 text-amber-400 focus:ring-amber-400"
                                   {{ isset($user->settings['auto_renew_token']) && $user->settings['auto_renew_token'] ? 'checked' : '' }}>
                            <label for="auto_renew_token" class="ml-2 text-slate-300">
                                {{ __('settings.strava.auto_renew_token') }}
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" id="sync_on_login" name="sync_on_login" value="1" 
                                   class="rounded bg-slate-700 border-slate-600 text-amber-400 focus:ring-amber-400"
                                   {{ isset($user->settings['sync_on_login']) && $user->settings['sync_on_login'] ? 'checked' : '' }}>
                            <label for="sync_on_login" class="ml-2 text-slate-300">
                                {{ __('settings.strava.sync_on_login') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Timezone & Language Settings -->
            <div class="space-y-6">
                <div class="border-b border-white border-opacity-20 pb-3">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-globe mr-2 text-amber-300"></i>
                        {{ __('settings.timezone_language.title') }}
                    </h2>
                </div>

                <div class="bg-slate-800 bg-opacity-50 p-6 rounded-xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="timezone" class="block text-slate-300 mb-2">{{ __('settings.timezone_language.timezone_label') }}</label>
                            <select id="timezone" name="timezone" 
                                    class="w-full bg-slate-700 border-slate-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                @php
                                    $timezones = \DateTimeZone::listIdentifiers();
                                    $currentTimezone = $user->settings['timezone'] ?? null;
                                @endphp
                                
                                @if (!$currentTimezone)
                                    <option value="" selected disabled>{{ __('settings.timezone_language.timezone_placeholder') }}</option>
                                @endif
                                
                                @foreach($timezones as $timezone)
                                    <option value="{{ $timezone }}" {{ $currentTimezone == $timezone ? 'selected' : '' }}>
                                        {{ $timezone }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-400">
                                <i class="fas fa-info-circle mr-1"></i> {{ __('settings.timezone_language.timezone_help') }}
                            </p>
                        </div>
                        
                        <div>
                            <label for="language" class="block text-slate-300 mb-2">{{ __('settings.timezone_language.language_label') }}</label>
                            <select id="language" name="language" 
                                    class="w-full bg-slate-700 border-slate-600 text-white rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="en" {{ ($user->settings['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="fr" {{ ($user->settings['language'] ?? 'en') == 'fr' ? 'selected' : '' }}>Français</option>
                            </select>
                            <p class="mt-1 text-xs text-slate-400">
                                <i class="fas fa-info-circle mr-1"></i> {{ __('settings.timezone_language.language_help') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Global Save Button -->
            <div class="pt-6 border-t border-white border-opacity-20">
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-500 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                    <i class="fas fa-save mr-2"></i>
                    {{ __('settings.messages.save_settings') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection