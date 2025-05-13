@extends('layouts.app')

@section('title', __('privacy.page_title'))
@section('meta_description', __('privacy.meta_description'))
@section('meta_keywords', __('privacy.meta_keywords'))

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shield-alt text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">{{ __('privacy.header.title') }}</h1>
            </div>

            <div class="prose prose-lg prose-invert max-w-none">
                <p class="text-cyan-200">{{ __('privacy.last_updated', ['date' => date('F d, Y')]) }}</p>
                
                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.introduction.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.introduction.content.paragraph_1') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('privacy.sections.introduction.content.paragraph_2') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.information_collected.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.information_collected.content') }}
                </p>
                <h3 class="text-xl font-bold text-white mt-6 mb-3">{{ __('privacy.sections.information_collected.personal_data.title') }}</h3>
                <p class="text-white">
                    {{ __('privacy.sections.information_collected.personal_data.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{{ __('privacy.sections.information_collected.personal_data.items.name_email') }}</li>
                    <li>{{ __('privacy.sections.information_collected.personal_data.items.credentials') }}</li>
                    <li>{{ __('privacy.sections.information_collected.personal_data.items.profile') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-white mt-6 mb-3">{{ __('privacy.sections.information_collected.training_data.title') }}</h3>
                <p class="text-white">
                    {{ __('privacy.sections.information_collected.training_data.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{{ __('privacy.sections.information_collected.training_data.items.activities') }}</li>
                    <li>{{ __('privacy.sections.information_collected.training_data.items.statistics') }}</li>
                    <li>{{ __('privacy.sections.information_collected.training_data.items.plans') }}</li>
                    <li>{{ __('privacy.sections.information_collected.training_data.items.metrics') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-white mt-6 mb-3">{{ __('privacy.sections.information_collected.strava_data.title') }}</h3>
                <p class="text-white">
                    {{ __('privacy.sections.information_collected.strava_data.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{{ __('privacy.sections.information_collected.strava_data.items.tokens') }}</li>
                    <li>{{ __('privacy.sections.information_collected.strava_data.items.activity_data') }}</li>
                    <li>{{ __('privacy.sections.information_collected.strava_data.items.history') }}</li>
                </ul>

                <h3 class="text-xl font-bold text-white mt-6 mb-3">{{ __('privacy.sections.information_collected.technical_data.title') }}</h3>
                <p class="text-white">
                    {{ __('privacy.sections.information_collected.technical_data.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{{ __('privacy.sections.information_collected.technical_data.items.device') }}</li>
                    <li>{{ __('privacy.sections.information_collected.technical_data.items.usage') }}</li>
                    <li>{{ __('privacy.sections.information_collected.technical_data.items.logs') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.how_we_use.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.how_we_use.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{{ __('privacy.sections.how_we_use.items.provide') }}</li>
                    <li>{{ __('privacy.sections.how_we_use.items.manage') }}</li>
                    <li>{{ __('privacy.sections.how_we_use.items.features') }}</li>
                    <li>{{ __('privacy.sections.how_we_use.items.analyze') }}</li>
                    <li>{{ __('privacy.sections.how_we_use.items.improve') }}</li>
                    <li>{{ __('privacy.sections.how_we_use.items.communicate') }}</li>
                    <li>{{ __('privacy.sections.how_we_use.items.technical') }}</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.sharing.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.sharing.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{!! __('privacy.sections.sharing.items.providers') !!}</li>
                    <li>{!! __('privacy.sections.sharing.items.consent') !!}</li>
                    <li>{!! __('privacy.sections.sharing.items.legal') !!}</li>
                    <li>{!! __('privacy.sections.sharing.items.rights') !!}</li>
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.third_party.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.third_party.content') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.security.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.security.content') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.rights.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.rights.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{!! __('privacy.sections.rights.items.access') !!}</li>
                    <li>{!! __('privacy.sections.rights.items.rectification') !!}</li>
                    <li>{!! __('privacy.sections.rights.items.erasure') !!}</li>
                    <li>{!! __('privacy.sections.rights.items.restriction') !!}</li>
                    <li>{!! __('privacy.sections.rights.items.portability') !!}</li>
                </ul>
                <p class="text-white mt-4">
                    {!! __('privacy.sections.rights.exercise_rights', ['contact_url' => route('contact.show')]) !!}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.cookies.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.cookies.content_1') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('privacy.sections.cookies.content_2') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.children.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.children.content') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.changes.title') }}</h2>
                <p class="text-white">
                    {{ __('privacy.sections.changes.content') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('privacy.sections.contact.title') }}</h2>
                <p class="text-white">
                    {!! __('privacy.sections.contact.content', ['contact_url' => route('contact.show')]) !!}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection