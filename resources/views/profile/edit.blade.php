@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl w-full space-y-8 bg-white p-8 rounded-2xl shadow-lg">
        <!-- En-tête -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">
                <i class="fas fa-user-cog mr-2"></i>
                Paramètres du compte
            </h1>
        </div>

        <!-- Contenu principal -->
        <div class="space-y-8">
            <!-- Informations personnelles -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-id-card mr-2"></i>
                        Informations personnelles
                    </h2>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nom complet -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nom complet
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user text-gray-400"></i>
                                </div>
                                <input type="text" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}" 
                                    required>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Adresse email
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                    name="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    required>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bouton de mise à jour -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium shadow-sm">
                            <i class="fas fa-save mr-2"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sécurité -->
            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-lock mr-2"></i>
                        Sécurité
                    </h2>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Mot de passe actuel -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Mot de passe actuel
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                    name="current_password" 
                                    required>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nouveau mot de passe -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Nouveau mot de passe
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock-open text-gray-400"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                    name="password" 
                                    required>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Confirmation
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle text-gray-400"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                                    name="password_confirmation" 
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de changement -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                            class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all duration-200 font-medium shadow-sm">
                            <i class="fas fa-sync-alt mr-2"></i>Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>

            <!-- Zone de danger -->
            <div class="space-y-6">
                <div class="border-b border-red-200 pb-4">
                    <h2 class="text-xl font-semibold text-red-600">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Zone de danger
                    </h2>
                </div>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="space-y-4">
                        <p class="text-red-600 font-medium">
                            <i class="fas fa-radiation mr-2"></i>
                            Cette action est irréversible. Toutes vos données seront supprimées.
                        </p>

                        <!-- Confirmation de mot de passe -->
                        <div class="max-w-xs space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Confirmez votre mot de passe
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-red-400"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg border border-red-300 focus:border-red-500 focus:ring-2 focus:ring-red-200" 
                                    name="password" 
                                    required>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Bouton de suppression -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-medium shadow-sm">
                            <i class="fas fa-trash-alt mr-2"></i>Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection