@extends('layouts.guest')

@push('head')
    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
    @endif
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-8 rounded-2xl shadow-lg bg-white bg-opacity-10 border-white border-opacity-20 border">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-white">
                {{ __('auth.register.title') }}
            </h2>
            <p class="mt-2 text-sm text-cyan-200">
                {{ __('auth.register.subtitle') }}
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('register') }}" id="register-form">
            @csrf
            @if(config('services.recaptcha.site_key'))
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
            @endif
            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="sr-only">{{ __('auth.register.name') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input type="text" id="name" name="name" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50"
                            placeholder="{{ __('auth.register.name') }}">
                    </div>
                    @error('name')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="sr-only">{{ __('auth.register.email') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-200" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50"
                            placeholder="{{ __('auth.register.email') }}">
                    </div>
                    @error('email')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">{{ __('auth.register.password') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50"
                            placeholder="{{ __('auth.register.password') }}">
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="sr-only">{{ __('auth.register.password_confirmation') }}</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50"
                            placeholder="{{ __('auth.register.password_confirmation') }}">
                    </div>
                </div>
            </div>

            <button type="submit" id="submit-btn"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                    text-white bg-cyan-600 hover:bg-cyan-500 font-medium shadow-sm transition-all duration-200">
                {{ __('auth.register.create_account') }}
            </button>

            <p class="mt-4 text-center text-sm text-cyan-200">
                {{ __('auth.register.already_registered') }}
                <a href="{{ route('login') }}" 
                   class="font-medium text-amber-300 transition-colors duration-200">
                    <span class="ml-1">{{ __('auth.login.sign_in') }}</span>
                </a>
            </p>
        </form>
    </div>
</div>

@if(config('services.recaptcha.site_key'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const submitBtn = document.getElementById('submit-btn');
    const recaptchaToken = document.getElementById('recaptcha_token');
    
    if (!form || !submitBtn || !recaptchaToken) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Désactiver le bouton pendant la vérification
        submitBtn.disabled = true;
        submitBtn.textContent = '{{ __("auth.register.verifying") }}...';
        
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config("services.recaptcha.site_key") }}', {
                action: 'register'
            }).then(function(token) {
                recaptchaToken.value = token;
                
                // Réactiver le bouton et soumettre le formulaire
                submitBtn.disabled = false;
                submitBtn.textContent = '{{ __("auth.register.create_account") }}';
                
                // Soumettre le formulaire
                form.submit();
            }).catch(function(error) {
                console.error('reCAPTCHA error:', error);
                
                // Réactiver le bouton en cas d'erreur
                submitBtn.disabled = false;
                submitBtn.textContent = '{{ __("auth.register.create_account") }}';
                
                // Afficher une erreur à l'utilisateur
                alert('{{ __("auth.register.recaptcha_error") }}');
            });
        });
    });
});
</script>
@endif

@endsection