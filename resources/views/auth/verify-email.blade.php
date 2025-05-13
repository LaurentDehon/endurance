@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center {{ themeClass('background') }} py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-8 rounded-2xl shadow-lg border bg-white bg-opacity-10 border-white border-opacity-20">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-white">
                {{ __('auth.verify_email.title') }}
            </h2>
            <p class="mt-2 text-sm text-cyan-200">
                {{ __('auth.verify_email.sent') }}
                {{ __('auth.verify_email.check') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('auth.verify_email.sent') }}
            </div>
        @endif

        <div class="mt-8 text-center space-y-6">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                           text-white bg-cyan-600 hover:bg-cyan-500 font-medium shadow-sm transition-all duration-200">
                    {{ __('auth.verify_email.resend') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="text-sm text-amber-300 transition-colors duration-200">
                    {{ __('auth.forgot_password.back_to_login') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection