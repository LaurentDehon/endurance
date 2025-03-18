<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-3xl font-bold text-gray-800">Users Management</h2>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Search users..." 
                       class="w-full sm:w-64 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" 
                            class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 transition-colors">
                        ✕
                    </button>
                @endif
            </div>

            <!-- Items per page -->
            <select wire:model.live="perPage" 
                    class="px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-auto px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        <th class="w-auto px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('email')">
                            Email
                            @include('components.sort-icon', ['field' => 'email'])
                        </th>
                        <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Verified
                        </th>
                        <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            Admin
                        </th>
                        <th class="w-64 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('last_login_at')">
                            Last Login
                            @include('components.sort-icon', ['field' => 'last_login_at'])
                        </th>
                        <th class="w-32 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Name -->
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                            
                            <!-- Email -->
                            <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                            
                            <!-- Verified -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($user->email_verified_at)
                                        <span class="text-green-500">
                                            {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-gray-500">Not verified</span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Admin -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    @if($user->is_admin)
                                        <span class="text-green-500">Yes</span>
                                    @else
                                        <span class="text-gray-500">No</span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Last Login -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $user->last_login_at?->diffForHumans() ?? '-' }}
                            </td>
                            
                            <!-- Actions -->
                            <td class="flex px-6 py-4 gap-4 whitespace-nowrap">
                                @if($user->email_verified_at)
                                    <button wire:click="verifyEmail({{ $user->id }})" class="text-red-600 hover:text-red-900" title="Unverify Email">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                @else
                                    <button wire:click="verifyEmail({{ $user->id }})" class="text-green-600 hover:text-green-900" title="Verify Email">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                @endif
                                @if($user->is_admin)
                                    <button wire:click="toggleAdmin({{ $user->id }})" class="text-red-600 hover:text-red-900" title="Revoke Admin">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
                                @else
                                    <button wire:click="toggleAdmin({{ $user->id }})" class="text-blue-600 hover:text-blue-900" title="Make Admin">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                @endif
                                <button wire:click="resendVerificationEmail({{ $user->id }})" class="text-blue-600 hover:text-blue-900" title="Resend Verification Email">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button wire:click="sendResetPassword({{ $user->id }})" class="text-purple-600 hover:text-purple-900" title="Send Password Reset">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button wire:click="deleteUser({{ $user->id }})" class="text-red-600 hover:text-red-900" title="Delete User">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No users found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>