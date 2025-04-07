@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center {{ themeClass('background') }} py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-8 rounded-2xl shadow-lg border {{ themeClass('card') }}">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold {{ themeClass('text-1') }}">
                Confirmation Required
            </h2>
            <p class="mt-2 text-sm {{ themeClass('text-2') }}">
                Please confirm your password to continue
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="space-y-5">
                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 {{ themeClass('text-2') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg {{ themeClass('input') }}"
                            placeholder="Password">
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                       {{ themeClass('button') }} font-medium shadow-sm transition-all duration-200">
                Confirm
            </button>

            @if (Route::has('password.request'))
                <p class="mt-4 text-center text-sm {{ themeClass('text-2') }}">
                    <a href="{{ route('password.request') }}" 
                       class="font-medium {{ themeClass('text-accent') }} transition-colors duration-200">
                        Forgot your password?
                    </a>
                </p>
            @endif
        </form>
    </div>
</div>
@endsection