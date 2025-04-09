@extends('layouts.app')
@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Fond d'écran fixe avec dégradé de couleur -->
    <div class="fixed inset-0 bg-gradient-to-br {{ themeClass('background') }} -z-10"></div>
    
    <div class="max-w-3xl w-full space-y-8 {{ themeClass('card') }} border backdrop-blur-lg p-8 rounded-2xl shadow-xl">
        <!-- Header avec style amélioré -->
        <div class="text-center">
            <h1 class="text-3xl font-bold {{ themeClass('text-1') }}">
                <i class="fas fa-user-cog mr-2 {{ themeClass('text-accent') }}"></i>
                Account Settings
            </h1>
            <p class="{{ themeClass('text-2') }} text-sm mt-2">Manage your account information and security</p>
        </div>

        <!-- Main Content -->
        <div class="space-y-10">
            <!-- Personal Information -->
            <div class="space-y-6">
                <div class="border-b {{ themeClass('divider') }} pb-3">
                    <h2 class="text-xl font-semibold {{ themeClass('text-1') }} flex items-center">
                        <i class="fas fa-id-card mr-2 {{ themeClass('text-accent') }}"></i>
                        Personal Information
                    </h2>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium {{ themeClass('text-1') }}">
                                Full Name
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-user {{ themeClass('text-3') }}"></i>
                                </div>
                                <input type="text" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all" 
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
                            <label class="block text-sm font-medium {{ themeClass('text-1') }}">
                                Email Address
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope {{ themeClass('text-3') }}"></i>
                                </div>
                                <input type="email" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all" 
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
                            class="{{ themeClass('button-accent') }} px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 font-medium">
                            <i class="fas fa-save mr-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security -->
            <div class="space-y-6">
                <div class="border-b {{ themeClass('divider') }} pb-3">
                    <h2 class="text-xl font-semibold {{ themeClass('text-1') }} flex items-center">
                        <i class="fas fa-lock mr-2 {{ themeClass('text-accent') }}"></i>
                        Security
                    </h2>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <!-- Current Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium {{ themeClass('text-1') }}">
                                Current Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key {{ themeClass('text-3') }}"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all" 
                                    name="current_password" 
                                    required>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium {{ themeClass('text-1') }}">
                                New Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock-open {{ themeClass('text-3') }}"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all" 
                                    name="password" 
                                    required>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div class="space-y-2">
                            <label class="block text-sm font-medium {{ themeClass('text-1') }}">
                                Confirm Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-check-circle {{ themeClass('text-3') }}"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all" 
                                    name="password_confirmation" 
                                    required>
                            </div>
                        </div>
                    </div>

                    <!-- Change Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                            class="{{ themeClass('button-accent') }} px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 font-medium">
                            <i class="fas fa-sync-alt mr-2"></i>Change Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Danger Zone -->
            <div class="space-y-6">
                <div class="border-b border-red-200/30 pb-3">
                    <h2 class="text-xl font-semibold text-red-500/90 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2 text-red-500/90"></i>
                        Danger Zone
                    </h2>
                </div>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="space-y-4">
                        <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                            <p class="text-red-400 font-medium flex items-start">
                                <i class="fas fa-radiation mr-2 mt-1"></i>
                                <span>This action is irreversible. All your data will be permanently deleted.</span>
                            </p>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="max-w-xs space-y-2 mt-4">
                            <label class="block text-sm font-medium {{ themeClass('text-1') }}">
                                Confirm Your Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-red-400"></i>
                                </div>
                                <input type="password" 
                                    class="pl-10 pr-4 py-3 w-full rounded-lg border border-red-300/30 bg-red-500/5 focus:border-red-400/50 focus:ring-2 focus:ring-red-400/20 text-white" 
                                    name="password" 
                                    required>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Delete Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit" 
                            class="{{ themeClass('button-danger') }} px-6 py-3 rounded-xl hover:shadow-lg transition-all duration-200 font-medium">
                            <i class="fas fa-trash-alt mr-2"></i>Permanently Delete
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection