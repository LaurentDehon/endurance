@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center {{ themeClass('background') }} py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-8 rounded-2xl shadow-lg border bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-white">
                Email Verification
            </h2>
            <p class="mt-2 text-sm text-cyan-200">
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
                           text-white bg-cyan-600 hover:bg-cyan-500 font-medium shadow-sm transition-all duration-200">
                    Resend Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="text-sm text-amber-300 transition-colors duration-200">
                    Back
                </button>
            </form>
        </div>
    </div>
</div>
@endsection