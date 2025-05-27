@extends('layouts.app')

@section('title', __('changelog.page_title'))
@section('meta_description', __('changelog.meta_description'))
@section('meta_keywords', __('changelog.meta_keywords'))

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-history text-xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ __('changelog.header.title') }}</h1>
                    <p class="text-cyan-200 mt-1">{{ __('changelog.header.subtitle') }}</p>
                </div>
            </div>

            <!-- Recent Updates -->
            <div>
                <h2 class="text-2xl font-bold text-white mb-6">{{ __('changelog.sections.recent_updates') }}</h2>
                <div class="space-y-6">
                    @foreach($changelog as $entry)
                    <div class="border-l-2 border-cyan-500 pl-4 py-1">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="h-2.5 w-2.5 rounded-full bg-cyan-500"></div>
                            <span class="bg-cyan-500 bg-opacity-20 text-cyan-300 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                {{ $entry['date'] }}
                            </span>
                        </div>
                        <ul class="list-disc list-inside text-white space-y-1 ml-2">
                            @foreach($entry['changes'] as $change)
                            <li>{{ $change }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Upcoming Features -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-white mb-6">{{ __('changelog.sections.upcoming_features') }}</h2>
                <ul class="grid gap-4">
                    @foreach($roadmap as $feature)
                    <li class="flex items-start gap-2 p-4 rounded-lg bg-cyan-600 bg-opacity-10">
                        <i class="fas fa-rocket text-cyan-400 mt-1"></i>
                        <span class="text-white">{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection