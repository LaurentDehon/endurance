@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Password Reset
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Enter your email to receive the reset link
            </p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="space-y-5">
                <!-- Email -->
                <div>
                    <label for="email" class="sr-only">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 placeholder-gray-400"
                            placeholder="Email address">
                    </div>
                    @error('email')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                       bg-blue-600 hover:bg-blue-700 
                       text-white font-medium shadow-sm transition-all duration-200">
                Send Reset Link
            </button>

            <p class="mt-4 text-center text-sm text-gray-600">
                <a href="{{ route('login') }}" 
                   class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                    Back to login
                </a>
            </p>
        </form>
    </div>
</div>
@endsection