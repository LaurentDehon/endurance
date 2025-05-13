@extends('layouts.app')

@section('title', __('terms.page_title'))
@section('meta_description', __('terms.meta_description'))
@section('meta_keywords', __('terms.meta_keywords'))

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white bg-opacity-10 border-white border-opacity-20 backdrop-blur-lg rounded-xl p-8 shadow-lg border">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-full text-white bg-cyan-600 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-scroll text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white">{{ __('terms.header.title') }}</h1>
            </div>

            <div class="prose prose-lg prose-invert max-w-none">
                <p class="text-cyan-200">{{ __('terms.header.last_updated', ['date' => date('F d, Y')]) }}</p>
                
                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.introduction.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.introduction.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.introduction.content.1') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.definitions.title') }}</h2>
                <ul class="list-disc pl-6 text-white space-y-2">
                    @foreach(__('terms.sections.definitions.items') as $item)
                        <li>{!! $item !!}</li>
                    @endforeach
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.account_terms.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.account_terms.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.account_terms.content.1') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.account_terms.content.2') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.account_terms.content.3') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.subscription_fees.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.subscription_fees.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.subscription_fees.content.1') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.subscription_fees.content.2') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.subscription_fees.content.3') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.user_responsibilities.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.user_responsibilities.content') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    @foreach(__('terms.sections.user_responsibilities.items') as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.intellectual_property.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.intellectual_property.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.intellectual_property.content.1') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.intellectual_property.content.2') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.data_privacy.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.data_privacy.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {!! __('terms.sections.data_privacy.content.1', ['privacyRoute' => route('privacy')]) !!}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.limitation_liability.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.limitation_liability.content.0') }}
                </p>
                <ul class="list-disc pl-6 text-white space-y-2">
                    <li>{{ __('terms.sections.limitation_liability.content.1') }}</li>
                    <li>{{ __('terms.sections.limitation_liability.content.2') }}</li>
                    <li>{{ __('terms.sections.limitation_liability.content.3') }}</li>
                    <li>{{ __('terms.sections.limitation_liability.content.4') }}</li>
                </ul>
                <p class="text-white mt-4">
                    {{ __('terms.sections.limitation_liability.content.5') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.disclaimer.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.disclaimer.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.disclaimer.content.1') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.disclaimer.content.2') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.indemnification.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.indemnification.content') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.termination.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.termination.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.termination.content.1') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.termination.content.2') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.changes.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.changes.content.0') }}
                </p>
                <p class="text-white mt-4">
                    {{ __('terms.sections.changes.content.1') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.governing_law.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.governing_law.content') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.severability.title') }}</h2>
                <p class="text-white">
                    {{ __('terms.sections.severability.content') }}
                </p>

                <h2 class="text-2xl font-bold text-white mt-8 mb-4">{{ __('terms.sections.contact.title') }}</h2>
                <p class="text-white">
                    {!! __('terms.sections.contact.content', ['contactRoute' => route('contact.show')]) !!}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection