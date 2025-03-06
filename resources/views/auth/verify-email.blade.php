@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-bold text-gray-900">
                Vérification email
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Un lien de vérification a été envoyé à votre adresse email
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                Un nouveau lien de vérification a été envoyé
            </div>
        @endif

        <div class="mt-8 text-center space-y-6">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" 
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg 
                           bg-blue-600 hover:bg-blue-700 
                           text-white font-medium shadow-sm transition-all duration-200">
                    Renvoyer l'email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                    class="text-sm text-blue-600 hover:text-blue-500 transition-colors duration-200">
                    Se déconnecter
                </button>
            </form>
        </div>
    </div>
</div>
@endsection