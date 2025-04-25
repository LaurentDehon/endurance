<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-4xl mx-auto">
        
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
                                {{ $user->email_verified_at ? 'Verified' : 'Not Verified' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $user->is_admin ? 'bg-purple-300 text-purple-800' : 'bg-gray-300 text-gray-800' }}">
                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 {{ $user->is_admin ? 'text-purple-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 8 8">
                                    <circle cx="4" cy="4" r="3" />
                                </svg>
                                {{ $user->is_admin ? 'Admin' : 'User' }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center px-4 py-2 rounded-lg">
                            <div class="text-2xl font-bold text-white">{{ $user->activities_count ?? 0 }}</div>
                            <div class="text-sm text-cyan-200">Activities</div>
                        </div>
                        <div class="text-center px-4 py-2 rounded-lg">
                            <div class="text-2xl font-bold text-white">{{ $user->workouts_count ?? 0 }}</div>
                            <div class="text-sm text-cyan-200">Workouts</div>
                        </div>
                        <div class="text-center px-4 py-2 rounded-lg">
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row sm:gap-20 justify-start mt-6">
                    <!-- Strava Information -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-white mb-2">Connection Information</h3>
                        <div class="flex flex-col text-cyan-200">
                            <!-- Adresse IP -->
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">Last IP Address: </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->last_ip_address)
                                        {{ $user->last_ip_address }}
                                    @else
                                        Not available
                                    @endif
                                </span>
                            </div>
                            
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">Last Login: </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->last_login_at && $user->last_login_at != '')
                                        {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
                                    @else
                                        Never
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Strava Information -->
                    <div class="mt-6">
                        <h3 class="text-lg font-semibold text-white mb-2">Strava Connection</h3>
                        <div class="flex flex-col text-cyan-200">
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">Token: </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->strava_token && $user->strava_expires_at && $user->strava_expires_at > time())
                                        Connected
                                    @else
                                        Not Connected
                                    @endif
                                </span>
                            </div>
                            
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">Expires: </span>
                                <span class="ml-1 font-mono text-sm">
                                    @if($user->strava_expires_at)
                                        {{ \Carbon\Carbon::createFromTimestamp($user->strava_expires_at)->diffForHumans() }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <!-- Admin toggle -->
            <button 
                wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : 'toggleAdmin' }}" 
                class="flex items-center justify-center p-4 rounded-lg shadow-md {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'text-white bg-cyan-600 hover:bg-cyan-500 transition-colors' }}"
                {{ ($user->is_admin && $user->name === 'admin') ? 'disabled' : '' }}
            >
                @if($user->is_admin)
                    <i class="fas fa-user-minus {{ ($user->name === 'admin') ? 'text-gray-400' : 'text-red-500' }} mr-3"></i>
                    <span>{{ ($user->name === 'admin') ? 'Cannot Revoke Admin' : 'Revoke Admin' }}</span>
                @else
                    <i class="fas fa-user-plus text-blue-500 mr-3"></i>
                    <span>Make Admin</span>
                @endif
            </button>
            
            <!-- Resend verification -->
            <button wire:click="resendVerificationEmail" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                <i class="fas fa-envelope text-yellow-500 mr-3"></i>
                <span>Resend Verification Email</span>
            </button>
            
            <!-- Password reset -->
            <button wire:click="sendResetPassword" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                <i class="fas fa-key text-purple-500 mr-3"></i>
                <span>Send Password Reset</span>
            </button>
                        
            <!-- Email verification -->
            <button wire:click="verifyEmail" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                @if($user->email_verified_at)
                    <i class="fas fa-times-circle text-red-500 mr-3"></i>
                    <span>Unverify Email</span>
                @else
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <span>Verify Email</span>
                @endif
            </button>
            
            <!-- Reset Strava -->
            <button wire:click="resetStravaConnection" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                <i class="fas fa-running text-orange-500 mr-3"></i>
                <span>Reset Strava Connection</span>
            </button>
            
            <!-- Send email - Utilise maintenant CustomModal -->
            <button wire:click="toggleEmailForm" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                <i class="fas fa-paper-plane text-blue-500 mr-3"></i>
                <span>Send Email</span>
            </button>
            
            <!-- Delete user - Occupe toute la largeur -->
            <button 
                wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : 'deleteUser' }}" 
                class="col-span-1 md:col-span-3 flex items-center justify-center p-4 rounded-lg shadow-md {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-red-100 text-red-800 hover:bg-red-200 transition-colors' }}"
                {{ ($user->is_admin && $user->name === 'admin') ? 'disabled' : '' }}
            >
                <i class="fas fa-trash {{ ($user->is_admin && $user->name === 'admin') ? 'text-gray-400' : 'text-red-500' }} mr-3"></i>
                <span>{{ ($user->is_admin && $user->name === 'admin') ? 'Cannot Delete Admin User' : 'Delete User' }}</span>
            </button>            
            
            <!-- Ban IP Address - Occupe toute la largeur -->
            <button 
                wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : ($user->last_ip_address ? 'banIpAddress' : '') }}" 
                class="col-span-1 md:col-span-3 flex items-center justify-center p-4 rounded-lg shadow-md {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : ($user->last_ip_address ? 'text-white bg-red-600 hover:bg-red-500' : 'bg-gray-200 text-gray-500 cursor-not-allowed') }} transition-colors"
                {{ ($user->is_admin && $user->name === 'admin') || !$user->last_ip_address ? 'disabled' : '' }}
            >
                <i class="fas fa-ban {{ ($user->is_admin && $user->name === 'admin') ? 'text-gray-400' : 'text-white' }} mr-3"></i>
                <span>
                    @if($user->is_admin && $user->name === 'admin')
                        Cannot Ban Admin IP
                    @elseif($user->last_ip_address)
                        Ban IP Address
                    @else
                        No IP Available
                    @endif
                </span>
            </button>
        </div>        
    </div>
</div>