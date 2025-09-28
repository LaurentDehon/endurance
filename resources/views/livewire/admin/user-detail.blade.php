<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- User info card -->
        <div class="backdrop-blur-lg rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="p-6 bg-white bg-opacity-10 border-white border-opacity-20 shadow-sm">
                <div class="flex flex-col md:flex-row justify-between">
                    <div class="mb-6 md:mb-0">
                        <h2 class="text-xl font-semibold text-white mb-2">{{ $user->name }}</h2>
                        <p class="text-cyan-200 mb-2">
                            <i class="fas fa-envelope mr-2"></i> {{ $user->email }}
                        </p>
                        <div class="flex space-x-3 mt-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-300 text-green-800' : 'bg-gray-300 text-gray-800' }}">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $user->email_verified_at ? 'text-green-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ $user->email_verified_at ? __('admin.users.table.status.verified') : __('admin.users.table.status.not_verified') }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $user->is_admin ? 'bg-purple-300 text-purple-800' : 'bg-gray-300 text-gray-800' }}">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $user->is_admin ? 'text-purple-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ $user->is_admin ? __('admin.users.table.status.admin') : __('admin.users.table.status.user') }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center px-4 py-2 rounded-lg">
                            <div class="text-2xl font-bold text-white">{{ $user->activities_count ?? 0 }}</div>
                            <div class="text-sm text-cyan-200">{{ __('admin.user_detail.activities') }}</div>
                        </div>
                        <div class="text-center px-4 py-2 rounded-lg">
                            <div class="text-2xl font-bold text-white">{{ $user->workouts_count ?? 0 }}</div>
                            <div class="text-sm text-cyan-200">{{ __('admin.user_detail.workouts') }}</div>
                        </div>
                        <div class="text-center px-4 py-2 rounded-lg">
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:gap-20 justify-start mt-6">
                    {{-- Informations de connexion et synchronisation --}}
                    {{-- 
                        Les dates sont stockées dans le fuseau horaire personnel de l'utilisateur
                        et affichées dans ce même fuseau pour garantir la cohérence
                    --}}
                    <!-- Connection Information -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-white mb-2">{{ __('admin.user_detail.connection_info') }}</h3>
                        <div class="flex flex-col text-cyan-200">
                            <!-- IP Address -->
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">{{ __('admin.user_detail.last_ip') }} </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->last_ip_address)
                                        {{ $user->last_ip_address }}
                                    @else
                                        {{ __('admin.user_detail.not_available') }}
                                    @endif
                                </span>
                            </div>
                            
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">{{ __('admin.user_detail.last_login') }} </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->last_login_at)
                                        @php
                                            $lastLogin = $user->last_login_at instanceof \Carbon\Carbon 
                                                ? $user->last_login_at 
                                                : \Carbon\Carbon::parse($user->last_login_at);
                                            // Convertir vers le fuseau horaire de l'utilisateur pour l'affichage
                                            $userTimezone = $user->settings['timezone'] ?? config('app.timezone');
                                            $lastLogin = $lastLogin->setTimezone($userTimezone);
                                        @endphp
                                        {{ $lastLogin->format('d/m/Y H:i:s') }}
                                        <span class="text-xs text-cyan-300 ml-1">({{ $lastLogin->diffForHumans() }})</span>
                                    @else
                                        {{ __('admin.user_detail.never') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Strava Information -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-white mb-2">{{ __('admin.user_detail.strava_connection') }}</h3>
                        <div class="flex flex-col text-cyan-200">
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">{{ __('admin.user_detail.token') }} </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->strava_token && $user->strava_expires_at && $user->strava_expires_at > time())
                                        {{ __('admin.user_detail.connected') }}
                                        <span class="text-sm ml-2">
                                            / {{ __('admin.user_detail.expires') }} {{ \Carbon\Carbon::createFromTimestamp($user->strava_expires_at)->diffForHumans() }}
                                        </span>
                                    @else
                                        {{ __('admin.user_detail.not_connected') }}
                                    @endif
                                </span>
                            </div>
                            
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">{{ __('admin.user_detail.last_sync') }} </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->last_sync_at)
                                        @php
                                            $lastSync = $user->last_sync_at instanceof \Carbon\Carbon 
                                                ? $user->last_sync_at 
                                                : \Carbon\Carbon::parse($user->last_sync_at);
                                            // Convertir vers le fuseau horaire de l'utilisateur pour l'affichage
                                            $userTimezone = $user->settings['timezone'] ?? config('app.timezone');
                                            $lastSync = $lastSync->setTimezone($userTimezone);
                                        @endphp
                                        {{ $lastSync->format('d/m/Y H:i:s') }}
                                        <span class="text-xs text-cyan-300 ml-1">({{ $lastSync->diffForHumans() }})</span>
                                    @else
                                        {{ __('admin.user_detail.never') }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions administratives -->
        <div class="space-y-6 mb-8">
            
            <!-- Section: Gestion du compte -->
            <div class="backdrop-blur-lg rounded-xl bg-white bg-opacity-10 border border-white border-opacity-20 p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-user-cog mr-3 text-cyan-400"></i>
                    Gestion du compte
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <!-- Admin toggle -->
                    <button 
                        wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : 'toggleAdmin' }}" 
                        class="group flex items-center justify-center p-4 rounded-lg shadow-md transition-all duration-200 {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-500 text-gray-300 cursor-not-allowed' : 'bg-indigo-700 hover:bg-indigo-600 text-white shadow-md hover:shadow-lg' }}"
                        {{ ($user->is_admin && $user->name === 'admin') ? 'disabled' : '' }}
                    >
                        <i class="fas {{ $user->is_admin ? 'fa-user-minus' : 'fa-user-plus' }} mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">
                            @if($user->is_admin)
                                {{ ($user->name === 'admin') ? __('admin.user_detail.buttons.cannot_revoke') : __('admin.user_detail.buttons.revoke_admin') }}
                            @else
                                {{ __('admin.user_detail.buttons.make_admin') }}
                            @endif
                        </span>
                    </button>
                    
                    <!-- Email verification -->
                    <button wire:click="verifyEmail" class="group flex items-center justify-center p-4 rounded-lg shadow-md transition-all duration-200 {{ $user->email_verified_at ? 'bg-red-700 hover:bg-red-600 text-white shadow-md hover:shadow-lg' : 'bg-green-700 hover:bg-green-600 text-white shadow-md hover:shadow-lg' }}">
                        <i class="fas {{ $user->email_verified_at ? 'fa-times-circle' : 'fa-check-circle' }} mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">
                            {{ $user->email_verified_at ? __('admin.user_detail.buttons.unverify_email') : __('admin.user_detail.buttons.verify_email') }}
                        </span>
                    </button>
                    
                    <!-- Send email -->
                    <button wire:click="toggleEmailForm" class="group flex items-center justify-center p-4 rounded-lg bg-indigo-700 hover:bg-indigo-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-paper-plane mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">{{ __('admin.user_detail.buttons.send_email') }}</span>
                    </button>
                </div>
            </div>

            <!-- Section: Authentification -->
            <div class="backdrop-blur-lg rounded-xl bg-white bg-opacity-10 border border-white border-opacity-20 p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fas fa-key mr-3 text-yellow-400"></i>
                    Authentification
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Password reset -->
                    <button wire:click="sendResetPassword" class="group flex items-center justify-center p-4 rounded-lg bg-teal-700 hover:bg-teal-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-key mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">{{ __('admin.user_detail.buttons.send_reset') }}</span>
                    </button>
                    
                    <!-- Resend verification -->
                    <button wire:click="resendVerificationEmail" class="group flex items-center justify-center p-4 rounded-lg bg-teal-700 hover:bg-teal-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-envelope mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">{{ __('admin.user_detail.buttons.resend_verification') }}</span>
                    </button>
                </div>
            </div>

            <!-- Section: Connexion Strava -->
            @if($user->strava_token || $user->strava_refresh_token)
            <div class="backdrop-blur-lg rounded-xl bg-white bg-opacity-10 border border-white border-opacity-20 p-6">
                <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                    <i class="fab fa-strava mr-3 text-orange-400"></i>
                    Connexion Strava
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Reset Strava -->
                    <button wire:click="resetStravaConnection" class="group flex items-center justify-center p-4 rounded-lg bg-orange-700 hover:bg-orange-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-sync-alt mr-3 group-hover:rotate-180 transition-transform duration-300"></i>
                        <span class="font-medium">{{ __('admin.user_detail.buttons.reset_strava') }}</span>
                    </button>
                    
                    <!-- Disconnect Strava -->
                    <button wire:click="disconnectStrava" class="group flex items-center justify-center p-4 rounded-lg bg-red-700 hover:bg-red-600 text-white shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-unlink mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">{{ __('admin.user_detail.buttons.disconnect_strava') }}</span>
                    </button>
                </div>
            </div>
            @endif

            <!-- Section: Actions dangereuses -->
            <div class="backdrop-blur-lg rounded-xl bg-red-900 bg-opacity-20 border border-red-500 border-opacity-30 p-6">
                <h3 class="text-lg font-semibold text-red-200 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-3 text-red-400"></i>
                    Zone de danger
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <!-- Ban IP Address -->
                    <button 
                        wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : ($user->last_ip_address ? 'banIpAddress' : '') }}" 
                        class="group flex items-center justify-center p-4 rounded-lg shadow-md transition-all duration-200 {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-500 text-gray-300 cursor-not-allowed' : ($user->last_ip_address ? 'bg-red-800 hover:bg-red-700 text-white shadow-md hover:shadow-lg' : 'bg-gray-500 text-gray-300 cursor-not-allowed') }}"
                        {{ ($user->is_admin && $user->name === 'admin') || !$user->last_ip_address ? 'disabled' : '' }}
                    >
                        <i class="fas fa-ban mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">
                            @if($user->is_admin && $user->name === 'admin')
                                {{ __('admin.user_detail.buttons.cannot_ban') }}
                            @elseif($user->last_ip_address)
                                {{ __('admin.user_detail.buttons.ban_ip') }}
                            @else
                                {{ __('admin.user_detail.buttons.no_ip') }}
                            @endif
                        </span>
                    </button>
                    
                    <!-- Delete user -->
                    <button 
                        wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : 'deleteUser' }}" 
                        class="group flex items-center justify-center p-4 rounded-lg shadow-md transition-all duration-200 {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-500 text-gray-300 cursor-not-allowed' : 'bg-red-800 hover:bg-red-700 text-white shadow-md hover:shadow-lg' }}"
                        {{ ($user->is_admin && $user->name === 'admin') ? 'disabled' : '' }}
                    >
                        <i class="fas fa-trash mr-3 group-hover:scale-110 transition-transform"></i>
                        <span class="font-medium">{{ ($user->is_admin && $user->name === 'admin') ? __('admin.user_detail.buttons.cannot_delete') : __('admin.user_detail.buttons.delete_user') }}</span>
                    </button>
                </div>
            </div>
        </div>        
    </div>
</div>