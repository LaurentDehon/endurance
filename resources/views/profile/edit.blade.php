@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-3xl">
        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-8 py-6 rounded-t-xl">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-user-cog mr-3"></i>
                    Account Settings
                </h1>
            </div>

            <!-- Main Content -->
            <div class="p-8 space-y-8">
                <!-- Personal Information Section -->
                <div class="space-y-6">
                    <div class="border-b-2 border-gray-100 pb-2">
                        <h2 class="text-lg font-semibold text-blue-600 uppercase tracking-wider">
                            <i class="fas fa-id-card mr-2"></i>
                            Personal Information
                        </h2>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-signature mr-2 text-blue-600"></i>
                                    Full Name
                                </label>
                                <input type="text" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 bg-white"
                                    name="name" 
                                    value="{{ old('name', $user->name) }}" 
                                    required>
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                    Email Address
                                </label>
                                <input type="email" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 bg-white"
                                    name="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    required>
                                @error('email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Update Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="px-6 py-3.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold shadow-lg shadow-blue-100 hover:shadow-md">
                                <i class="fas fa-save mr-2"></i>Update
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Section -->
                <div class="space-y-6">
                    <div class="border-b-2 border-gray-100 pb-2">
                        <h2 class="text-lg font-semibold text-blue-600 uppercase tracking-wider">
                            <i class="fas fa-lock mr-2"></i>
                            Security
                        </h2>
                    </div>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Current Password -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-key mr-2 text-blue-600"></i>
                                    Current Password
                                </label>
                                <input type="password" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 bg-white"
                                    name="current_password" 
                                    required>
                                @error('current_password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- New Password -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-lock-open mr-2 text-blue-600"></i>
                                    New Password
                                </label>
                                <input type="password" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 bg-white"
                                    name="password" 
                                    required>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Confirmation -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-check-circle mr-2 text-blue-600"></i>
                                    Confirmation
                                </label>
                                <input type="password" 
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 bg-white"
                                    name="password_confirmation" 
                                    required>
                            </div>
                        </div>

                        <!-- Change Password Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="px-6 py-3.5 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors font-semibold shadow-lg shadow-purple-100 hover:shadow-md">
                                <i class="fas fa-sync-alt mr-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Danger Zone -->
                <div class="space-y-6">
                    <div class="border-b-2 border-red-100 pb-2">
                        <h2 class="text-lg font-semibold text-red-600 uppercase tracking-wider">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Danger Zone
                        </h2>
                    </div>

                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="space-y-4">
                            <p class="text-red-600 font-medium">
                                <i class="fas fa-radiation mr-2"></i>
                                Deleting your account is irreversible. All your data will be permanently lost.
                            </p>

                            <!-- Password Confirmation -->
                            <div class="max-w-xs space-y-2">
                                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                                    <i class="fas fa-key mr-2 text-red-600"></i>
                                    Confirm Your Password
                                </label>
                                <input type="password" 
                                    class="w-full px-4 py-3 border-2 border-red-200 rounded-xl focus:ring-4 focus:ring-red-200 focus:border-red-500 bg-white"
                                    name="password" 
                                    required>
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Delete Account Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" 
                                class="px-6 py-3.5 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-semibold shadow-lg shadow-red-100 hover:shadow-md">
                                <i class="fas fa-trash-alt mr-2"></i>Delete Permanently
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
