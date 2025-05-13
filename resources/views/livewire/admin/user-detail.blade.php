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
                                    @if($user->last_login_at && $user->last_login_at != '')
                                        {{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}
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
                                    @else
                                        {{ __('admin.user_detail.not_connected') }}
                                    @endif
                                </span>
                            </div>
                            
                            <div class="inline-flex items-center py-1 rounded-lg">
                                <span class="text-sm">{{ __('admin.user_detail.expires') }} </span>
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-8">
            <!-- Admin toggle -->
            <button 
                wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : 'toggleAdmin' }}" 
                class="flex items-center justify-center p-4 rounded-lg shadow-md {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'text-white bg-cyan-600 hover:bg-cyan-500 transition-colors' }}"
                {{ ($user->is_admin && $user->name === 'admin') ? 'disabled' : '' }}
            >
                @if($user->is_admin)
                    {{-- <i class="fas fa-user-minus {{ ($user->name === 'admin') ? 'text-gray-400' : 'text-red-500' }} mr-3"></i> --}}
                    <span>{{ ($user->name === 'admin') ? __('admin.user_detail.buttons.cannot_revoke') : __('admin.user_detail.buttons.revoke_admin') }}</span>
                @else
                    {{-- <i class="fas fa-user-plus text-blue-500 mr-3"></i> --}}
                    <span>{{ __('admin.user_detail.buttons.make_admin') }}</span>
                @endif
            </button>
            
            <!-- Resend verification -->
            <button wire:click="resendVerificationEmail" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                {{-- <i class="fas fa-envelope text-yellow-500 mr-3"></i> --}}
                <span>{{ __('admin.user_detail.buttons.resend_verification') }}</span>
            </button>
            
            <!-- Password reset -->
            <button wire:click="sendResetPassword" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                {{-- <i class="fas fa-key text-purple-500 mr-3"></i> --}}
                <span>{{ __('admin.user_detail.buttons.send_reset') }}</span>
            </button>
                        
            <!-- Email verification -->
            <button wire:click="verifyEmail" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                @if($user->email_verified_at)
                    {{-- <i class="fas fa-times-circle text-red-500 mr-3"></i> --}}
                    <span>{{ __('admin.user_detail.buttons.unverify_email') }}</span>
                @else
                    {{-- <i class="fas fa-check-circle text-green-500 mr-3"></i> --}}
                    <span>{{ __('admin.user_detail.buttons.verify_email') }}</span>
                @endif
            </button>
            
            <!-- Reset Strava -->
            <button wire:click="resetStravaConnection" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                {{-- <i class="fas fa-running text-orange-500 mr-3"></i> --}}
                <span>{{ __('admin.user_detail.buttons.reset_strava') }}</span>
            </button>
            
            <!-- Send email -->
            <button wire:click="toggleEmailForm" class="flex items-center justify-center p-4 rounded-lg shadow-md text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
                {{-- <i class="fas fa-paper-plane text-blue-500 mr-3"></i> --}}
                <span>{{ __('admin.user_detail.buttons.send_email') }}</span>
            </button>
            
            <!-- Delete user - Full width -->
            <button 
                wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : 'deleteUser' }}" 
                class="col-span-1 md:col-span-3 flex items-center justify-center p-4 rounded-lg shadow-md {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-red-100 text-red-800 hover:bg-red-200 transition-colors' }}"
                {{ ($user->is_admin && $user->name === 'admin') ? 'disabled' : '' }}
            >
                {{-- <i class="fas fa-trash {{ ($user->is_admin && $user->name === 'admin') ? 'text-gray-400' : 'text-red-500' }} mr-3"></i> --}}
                <span>{{ ($user->is_admin && $user->name === 'admin') ? __('admin.user_detail.buttons.cannot_delete') : __('admin.user_detail.buttons.delete_user') }}</span>
            </button>            
            
            <!-- Ban IP Address - Full width -->
            <button 
                wire:click="{{ ($user->is_admin && $user->name === 'admin') ? '' : ($user->last_ip_address ? 'banIpAddress' : '') }}" 
                class="col-span-1 md:col-span-3 flex items-center justify-center p-4 rounded-lg shadow-md {{ ($user->is_admin && $user->name === 'admin') ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : ($user->last_ip_address ? 'text-white bg-red-600 hover:bg-red-500' : 'bg-gray-200 text-gray-500 cursor-not-allowed') }} transition-colors"
                {{ ($user->is_admin && $user->name === 'admin') || !$user->last_ip_address ? 'disabled' : '' }}
            >
                {{-- <i class="fas fa-ban {{ ($user->is_admin && $user->name === 'admin') ? 'text-gray-400' : 'text-white' }} mr-3"></i> --}}
                <span>
                    @if($user->is_admin && $user->name === 'admin')
                        {{ __('admin.user_detail.buttons.cannot_ban') }}
                    @elseif($user->last_ip_address)
                        {{ __('admin.user_detail.buttons.ban_ip') }}
                    @else
                        {{ __('admin.user_detail.buttons.no_ip') }}
                    @endif
                </span>
            </button>
        </div>        
    </div>
</div>