@extends('layouts.app')
@section('content')
<div class="contact-container mx-auto p-4 md:p-8 max-w-7xl">
    <!-- Header Section -->
    <div class="bg-white bg-opacity-10 backdrop-blur-lg rounded-xl shadow-lg mb-8 py-6">
        <div class="container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">{{ __('contact.header.title') }}</h1>
            <p class="text-lg md:text-xl text-cyan-200 max-w-2xl mx-auto mb-4">
                {{ __('contact.header.subtitle') }}
            </p>
        </div>
    </div>

    <!-- Contact Form Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Main Contact Form -->
        <div class="md:col-span-2">
            <div class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center">
                        <i class="fas fa-envelope text-xl text-white"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('contact.form.title') }}</h2>
                </div>
                
                <form class="contact-form mt-8 space-y-6" action="{{ route('contact.send') }}" method="POST">
                    @csrf

                    <div class="rounded-md space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-white mb-1">{{ __('contact.form.name') }}</label>
                                <input id="name" name="name" type="text" required placeholder="{{ __('contact.form.name_placeholder') }}" class="input w-full h-10 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-white mb-1">{{ __('contact.form.email') }}</label>
                                <input id="email" name="email" type="email" required placeholder="{{ __('contact.form.email_placeholder') }}" class="input w-full h-10 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-white mb-1">{{ __('contact.form.subject') }}</label>
                            <input id="subject" name="subject" type="text" required placeholder="{{ __('contact.form.subject_placeholder') }}" class="input w-full h-10 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-white mb-1">{{ __('contact.form.message') }}</label>
                            <textarea id="message" name="message" rows="6" required placeholder="{{ __('contact.form.message_placeholder') }}" class="input w-full px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400"></textarea>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-500">
                            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                <i class="fas fa-paper-plane"></i>
                            </span>
                            {{ __('contact.form.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Contact Info Sidebar -->
        <div class="md:col-span-1">
            <div class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg mb-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center">
                        <i class="fas fa-info text-xl text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ __('contact.info.title') }}</h2>
                </div>
                
                <ul class="space-y-4">
                    <li class="flex items-center gap-3">
                        <div class="mt-1 text-white"><i class="fas fa-envelope"></i></div>
                        <span class="text-white">{{ __('contact.info.email') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="mt-1 text-white"><i class="fas fa-phone"></i></div>
                        <span class="text-white">{{ __('contact.info.phone') }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="mt-1 text-white"><i class="fas fa-clock"></i></div>
                        <span class="text-white">{{ __('contact.info.hours') }}</span>
                    </li>
                </ul>
            </div>
            
            <div class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center">
                        <i class="fas fa-share-alt text-xl text-white"></i>
                    </div>
                    <h2 class="text-xl font-bold text-white">{{ __('contact.social.title') }}</h2>
                </div>
                
                <div class="flex justify-center gap-6 my-4">
                    <a href="#" class="w-10 h-10 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-twitter text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-facebook text-white"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center hover:opacity-80 transition-opacity duration-300">
                        <i class="fab fa-instagram text-white"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- FAQ Section -->
    <div class="section-card bg-white bg-opacity-10 backdrop-blur-lg rounded-xl p-6 md:p-8 shadow-lg mt-8">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full text-white bg-cyan-600 hover:bg-cyan-500 flex items-center justify-center">
                <i class="fas fa-question text-xl text-white"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-bold text-white">{{ __('contact.faq.title') }}</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm p-5 rounded-xl">
                <h3 class="text-lg font-semibold text-white mb-2">{{ __('contact.faq.questions.response_time.question') }}</h3>
                <p class="text-cyan-200">
                    {{ __('contact.faq.questions.response_time.answer') }}
                </p>
            </div>
            
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm p-5 rounded-xl">
                <h3 class="text-lg font-semibold text-white mb-2">{{ __('contact.faq.questions.technical_support.question') }}</h3>
                <p class="text-cyan-200">
                    {{ __('contact.faq.questions.technical_support.answer') }}
                </p>
            </div>
            
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm p-5 rounded-xl">
                <h3 class="text-lg font-semibold text-white mb-2">{{ __('contact.faq.questions.bug_report.question') }}</h3>
                <p class="text-cyan-200">
                    {{ __('contact.faq.questions.bug_report.answer') }}
                </p>
            </div>
            
            <div class="bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm p-5 rounded-xl">
                <h3 class="text-lg font-semibold text-white mb-2">{{ __('contact.faq.questions.feature_request.question') }}</h3>
                <p class="text-cyan-200">
                    {{ __('contact.faq.questions.feature_request.answer') }}
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    html, body {
        height: 100%;
        overflow-y: auto;
        margin: 0;
        padding: 0;
    }
    
    .contact-container {
        overflow-y: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    
    .contact-container::-webkit-scrollbar {
        display: none;
    }
    
    .contact-header {
        position: relative;
        padding: 3rem 0;
        overflow: hidden;
        border-radius: 1rem;
    }
    
    .contact-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.7) 0%, rgba(16, 24, 39, 0.8) 100%);
        z-index: -2;
    }
    
    .contact-form input:focus, 
    .contact-form textarea:focus {
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }
    
    .input {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    
    .input:focus, .input:active, .input:hover {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        border-width: 0 !important;
        border-color: transparent !important;
        ring-width: 0 !important;
    }
    
    .contact-card {
        transition: transform 0.3s ease;
        position: relative;
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
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
</style>
@endsection