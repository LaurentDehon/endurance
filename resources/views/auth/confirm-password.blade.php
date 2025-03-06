@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Confirmation requise
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Veuillez confirmer votre mot de passe pour continuer
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('password.confirm') }}">
            @csrf
            <div class="space-y-5">
                <!-- Password -->
                <div>
                    <label for="password" class="sr-only">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="pl-10 pr-4 py-3 w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 placeholder-gray-400"
                            placeholder="Mot de passe">
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <button type="submit" 
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                       bg-blue-600 hover:bg-blue-700 
                       text-white font-medium shadow-sm transition-all duration-200">
                Confirmer
            </button>

            @if (Route::has('password.request'))
                <p class="mt-4 text-center text-sm text-gray-600">
                    <a href="{{ route('password.request') }}" 
                       class="font-medium text-blue-600 hover:text-blue-500 transition-colors duration-200">
                        Mot de passe oublié ?
                    </a>
                </p>
            @endif
        </form>
    </div>
</div>
@endsection