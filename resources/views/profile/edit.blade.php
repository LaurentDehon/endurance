@extends('layouts.app')
@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Fond d'écran fixe avec dégradé de couleur -->
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 -z-10"></div>
    
    <div class="max-w-3xl w-full space-y-8 bg-white bg-opacity-10 border-white border-opacity-20 border backdrop-blur-lg p-8 rounded-2xl shadow-lg">
        <!-- Header avec style amélioré -->
        <div class="text-center">
            <h1 class="text-3xl font-bold text-white">
                <i class="fas fa-user-cog mr-2 text-amber-300"></i>
                {{ __('profile.page_title') }}
            </h1>
            <p class="text-cyan-200 text-sm mt-2">{{ __('profile.page_description') }}</p>
        </div>

        <!-- Main Content -->
        <div class="space-y-10">
            <!-- Personal Information -->
            <div class="space-y-6">
                <div class="border-b border-white border-opacity-20 pb-3">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-id-card mr-2 text-amber-300"></i>
                        {{ __('profile.personal_information.title') }}
                    </h2>
                </div>

                <div class="bg-slate-800 bg-opacity-50 p-6 rounded-xl">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-white">
                                    {{ __('profile.personal_information.full_name') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-slate-300"></i>
                                    </div>
                                    <input type="text" 
                                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all" 
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
                                <label class="block text-sm font-medium text-white">
                                    {{ __('profile.personal_information.email_address') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-slate-300"></i>
                                    </div>
                                    <input type="email" 
                                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all" 
                                        name="email" 
                                        value="{{ old('email', $user->email) }}" 
                                        required>
                                </div>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Update Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                                <i class="fas fa-save mr-2"></i>{{ __('profile.personal_information.update_profile') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security -->
            <div class="space-y-6">
                <div class="border-b border-white border-opacity-20 pb-3">
                    <h2 class="text-xl font-semibold text-white flex items-center">
                        <i class="fas fa-lock mr-2 text-amber-300"></i>
                        {{ __('profile.security.title') }}
                    </h2>
                </div>

                <div class="bg-slate-800 bg-opacity-50 p-6 rounded-xl">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Current Password -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-white">
                                    {{ __('profile.security.current_password') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-slate-300"></i>
                                    </div>
                                    <input type="password" 
                                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all" 
                                        name="current_password" 
                                        required>
                                </div>
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-white">
                                    {{ __('profile.security.new_password') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-lock-open text-slate-300"></i>
                                    </div>
                                    <input type="password" 
                                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all" 
                                        name="password" 
                                        required>
                                </div>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmation -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-white">
                                    {{ __('profile.security.confirm_password') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-check-circle text-slate-300"></i>
                                    </div>
                                    <input type="password" 
                                        class="pl-10 pr-4 py-3 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all" 
                                        name="password_confirmation" 
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Change Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                                <i class="fas fa-sync-alt mr-2"></i>{{ __('profile.security.change_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="space-y-6">
                <div class="border-b border-red-200/30 pb-3">
                    <h2 class="text-xl font-semibold text-red-400 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-400/90"></i>
                        {{ __('profile.danger_zone.title') }}
                    </h2>
                </div>

                <div class="bg-slate-800 bg-opacity-50 p-6 rounded-xl border border-red-400/10">
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="space-y-4">
                            <div class="p-4 rounded-lg bg-red-400/10 border border-red-400/20">
                                <p class="text-red-400 font-medium flex items-start">
                                    <i class="fas fa-radiation mr-2 mt-1"></i>
                                    <span>{{ __('profile.danger_zone.warning') }}</span>
                                </p>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="max-w-xs space-y-2 mt-4">
                                <label class="block text-sm font-medium text-white">
                                    {{ __('profile.danger_zone.confirm_password') }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-key text-red-400"></i>
                                    </div>
                                    <input type="password" 
                                        class="pl-10 pr-4 py-3 w-full rounded-lg border border-red-300/30 bg-red-400/5 focus:border-red-400/50 focus:ring-2 focus:ring-red-400/20 text-white" 
                                        name="password" 
                                        required>
                                </div>
                                @error('password')
                                    <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Delete Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="bg-red-600 hover:bg-red-400 text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
                                <i class="fas fa-trash-alt mr-2"></i>{{ __('profile.danger_zone.delete_account') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection