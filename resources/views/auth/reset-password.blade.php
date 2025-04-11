@extends('layouts.guest')
@section('content')
<div class="min-h-screen flex items-center justify-center {{ themeClass('background') }} py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 p-8 rounded-2xl shadow-lg border bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-white">
                New Password
            </h2>
            <p class="mt-2 text-sm text-cyan-200">
                Enter your new password
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="space-y-5">
                <!-- Email -->
                <input type="hidden" id="email" name="email" value="{{ old('email', $request->email) }}">

                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50"
                            placeholder="New password">
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="sr-only">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-cyan-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50"
                            placeholder="Confirm password">
                    </div>
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                       text-white bg-cyan-600 hover:bg-cyan-500 font-medium shadow-sm transition-all duration-200">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection