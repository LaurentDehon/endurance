@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center {{ themeClass('background') }} py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-8 rounded-2xl shadow-lg border {{ themeClass('card') }}">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold {{ themeClass('text-1') }}">
                Email Verification
            </h2>
            <p class="mt-2 text-sm {{ themeClass('text-2') }}">
                A verification link has been sent to your email address. Please check your spam folder if you don't see it in your inbox.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                A new verification link has been sent
            </div>
        @endif

        <div class="mt-8 text-center space-y-6">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                           {{ themeClass('button') }} font-medium shadow-sm transition-all duration-200">
                    Resend Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="text-sm {{ themeClass('text-accent') }} transition-colors duration-200">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection