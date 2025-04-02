<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))]">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6 gap-4">        
        <div class="flex gap-4 w-full ml-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Search users..." 
                       class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" 
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 transition-colors">
                        ✕
                    </button>
                @endif
            </div>

            <!-- Items per page -->
            <select wire:model.live="perPage" class="w-28 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                <option value="10">10/page</option>
                <option value="25">25/page</option>
                <option value="50">50/page</option>
            </select>
        </div>
    </div>

    <!-- Table with improved responsiveness -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hidden sm:table-cell" 
                            wire:click="sortBy('email')">
                            Email
                            @include('components.sort-icon', ['field' => 'email'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('activities_count')">
                            Activities
                            @include('components.sort-icon', ['field' => 'activities_count'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('trainings_count')">
                            Trainings
                            @include('components.sort-icon', ['field' => 'trainings_count'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Verified
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                            Admin
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('last_login_at')">
                            Last Login
                            @include('components.sort-icon', ['field' => 'last_login_at'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Name with tooltip for long names -->
                            <td class="px-4 sm:px-6 py-4 max-w-[150px] sm:max-w-none truncate">
                                <div class="text-sm font-medium text-gray-900" title="{{ $user->name }}">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs text-gray-500 sm:hidden truncate">
                                    {{ $user->email }}
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm hidden sm:table-cell">
                                <div class="text-gray-900">{{ $user->email }}</div>
                            </td>
                            
                            <!-- Activities Count -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="text-gray-900">{{ $user->activities_count ?? 0 }}</div>
                            </td>
                            
                            <!-- Trainings Count -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="text-gray-900">{{ $user->trainings_count ?? 0 }}</div>
                            </td>
                            
                            <!-- Verified with better visual indicators -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Admin -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-purple-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        User
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Last Login -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
                                @if($user->last_login_at && $user->last_login_at != '')
                                    @php
                                        try {
                                            $lastLoginDate = $user->last_login_at instanceof \Carbon\Carbon 
                                                ? $user->last_login_at 
                                                : \Carbon\Carbon::parse($user->last_login_at);
                                        } catch (\Exception $e) {
                                            $lastLoginDate = null;
                                        }
                                    @endphp
                                    
                                    <span title="{{ $lastLoginDate ? $lastLoginDate->format('d/m/Y H:i:s') : '' }}">
                                        {{ $lastLoginDate ? $lastLoginDate->diffForHumans() : '-' }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            
                            <!-- Actions optimized for touch -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center items-center gap-0">
                                    @if($user->email_verified_at)
                                        <button wire:click="verifyEmail({{ $user->id }})" 
                                                class="text-red-600 hover:text-red-900 p-1.5 rounded-full hover:bg-red-100 transition-colors"
                                                title="Unverify Email">
                                            <i class="fas fa-times-circle text-sm sm:text-base"></i>
                                        </button>
                                    @else
                                        <button wire:click="verifyEmail({{ $user->id }})" 
                                                class="text-green-600 hover:text-green-900 p-1.5 rounded-full hover:bg-green-100 transition-colors"
                                                title="Verify Email">
                                            <i class="fas fa-check-circle text-sm sm:text-base"></i>
                                        </button>
                                    @endif
                                    @if($user->is_admin)
                                        <button wire:click="toggleAdmin({{ $user->id }})" 
                                                class="text-red-600 hover:text-red-900 p-1.5 rounded-full hover:bg-red-100 transition-colors"
                                                title="Revoke Admin">
                                            <i class="fas fa-user-minus text-sm sm:text-base"></i>
                                        </button>
                                    @else
                                        <button wire:click="toggleAdmin({{ $user->id }})" 
                                                class="text-blue-600 hover:text-blue-900 p-1.5 rounded-full hover:bg-blue-100 transition-colors"
                                                title="Make Admin">
                                            <i class="fas fa-user-plus text-sm sm:text-base"></i>
                                        </button>
                                    @endif
                                    <button wire:click="resendVerificationEmail({{ $user->id }})" 
                                            class="text-blue-600 hover:text-blue-900 p-1.5 rounded-full hover:bg-blue-100 transition-colors"
                                            title="Resend Verification Email">
                                        <i class="fas fa-envelope text-sm sm:text-base"></i>
                                    </button>
                                    <button wire:click="sendResetPassword({{ $user->id }})" 
                                            class="text-purple-600 hover:text-purple-900 p-1.5 rounded-full hover:bg-purple-100 transition-colors"
                                            title="Send Password Reset">
                                        <i class="fas fa-key text-sm sm:text-base"></i>
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            class="text-red-600 hover:text-red-900 p-1.5 rounded-full hover:bg-red-100 transition-colors"
                                            title="Delete User">
                                        <i class="fas fa-trash text-sm sm:text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                <div class="py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>