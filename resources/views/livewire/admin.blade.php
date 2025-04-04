<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))] admin-container">
    <div class="flex justify-between items-center mb-6 gap-4">        
        <div class="flex gap-4 w-full ml-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search..." 
                       class="w-full h-10 px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-200 focus:ring-0 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 {{ themeClass('text-2') }} transition-colors">✕</button>
                @endif
            </div>

            <!-- Items per page -->
            <select wire:model.live="perPage" class="w-28 px-4 py-2 rounded-lg">
                <option value="10">10/page</option>
                <option value="25">25/page</option>
                <option value="50">50/page</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="backdrop-blur-lg rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto border rounded-t-xl {{ themeClass('table-border') }}">
            <table class="min-w-full divide-y {{ themeClass('table') }}">
                <thead">
                    <tr>
                        <!-- Headings -->
                        <th class="px-4 py-3 text-left text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer hidden sm:table-cell" 
                            wire:click="sortBy('email')">
                            Email
                            @include('components.sort-icon', ['field' => 'email'])
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('activities_count')">
                            Activities
                            @include('components.sort-icon', ['field' => 'activities_count'])
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('trainings_count')">
                            Trainings
                            @include('components.sort-icon', ['field' => 'trainings_count'])
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase">
                            Verified
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase">
                            Admin
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('last_login_at')">
                            Last Login
                            @include('components.sort-icon', ['field' => 'last_login_at'])
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $loop->even ? 'bg-white bg-opacity-10' : 'bg-black bg-opacity-10' }}">
                            <!-- Name with tooltip for long names -->
                            <td class="px-4 py-4 max-w-[150px] sm:max-w-none truncate">
                                <div class="text-sm font-medium {{ themeClass('text-2') }}" title="{{ $user->name }}">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs {{ themeClass('text-2') }} sm:hidden truncate">
                                    {{ $user->email }}
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm hidden sm:table-cell">
                                <div class="{{ themeClass('text-2') }}">{{ $user->email }}</div>
                            </td>
                            
                            <!-- Activities Count -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                <div class="{{ themeClass('text-2') }}">{{ $user->activities_count ?? 0 }}</div>
                            </td>
                            
                            <!-- Trainings Count -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                <div class="{{ themeClass('text-2') }}">{{ $user->trainings_count ?? 0 }}</div>
                            </td>
                            
                            <!-- Verified -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-green-300 text-green-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-gray-300 text-gray-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Pending
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Admin -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-purple-300 text-purple-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-purple-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-gray-300 text-gray-800">
                                        <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-gray-400" fill="currentColor" viewBox="0 0 8 8">
                                            <circle cx="4" cy="4" r="3" />
                                        </svg>
                                        User
                                    </span>
                                @endif
                            </td>
                            
                            <!-- Last Login -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
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
                                        <div class="{{ themeClass('text-2') }}">{{ $lastLoginDate ? $lastLoginDate->diffForHumans() : '-' }}</div>
                                    </span>
                                @else
                                <div class="{{ themeClass('text-2') }}">-</div>
                                @endif
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex justify-center items-center gap-3">
                                    @if($user->email_verified_at)
                                        <button wire:click="verifyEmail({{ $user->id }})" 
                                                class="text-red-400 hover:text-red-500"
                                                title="Unverify Email">
                                            <i class="fas fa-times-circle text-sm sm:text-base"></i>
                                        </button>
                                    @else
                                        <button wire:click="verifyEmail({{ $user->id }})" 
                                                class="text-green-400 hover:text-green-500"
                                                title="Verify Email">
                                            <i class="fas fa-check-circle text-sm sm:text-base"></i>
                                        </button>
                                    @endif
                                    @if($user->is_admin)
                                        <button wire:click="toggleAdmin({{ $user->id }})" 
                                                class="text-red-400 hover:text-red-500"
                                                title="Revoke Admin">
                                            <i class="fas fa-user-minus text-sm sm:text-base"></i>
                                        </button>
                                    @else
                                        <button wire:click="toggleAdmin({{ $user->id }})" 
                                                class="text-blue-400 hover:text-blue-500"
                                                title="Make Admin">
                                            <i class="fas fa-user-plus text-sm sm:text-base"></i>
                                        </button>
                                    @endif
                                    <button wire:click="resendVerificationEmail({{ $user->id }})" 
                                            class="text-yellow-400 hover:text-yellow-500"
                                            title="Resend Verification Email">
                                        <i class="fas fa-envelope text-sm sm:text-base"></i>
                                    </button>
                                    <button wire:click="sendResetPassword({{ $user->id }})" 
                                            class="text-purple-400 hover:text-purple-500"
                                            title="Send Password Reset">
                                        <i class="fas fa-key text-sm sm:text-base"></i>
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            class="text-red-400 hover:text-red-500"
                                            title="Delete User">
                                        <i class="fas fa-trash text-sm sm:text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center {{ themeClass('text-2') }}">
                           <div class="flex justify-center items-center py-6">
                                <span class="font-medium">No user found</span>
                           </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="p-4 border-t {{ themeClass('table') }}">
                <div class="custom-pagination">
                    <div class="pagination-summary {{ themeClass('text-2') }}">
                        Showing 
                        <span class="font-medium mx-1 {{ themeClass('text-1') }}">{{ $users->firstItem() }}</span> 
                        to 
                        <span class="font-medium mx-1 {{ themeClass('text-1') }}">{{ $users->lastItem() }}</span> 
                        of 
                        <span class="font-medium mx-1 {{ themeClass('text-1') }}">{{ $users->total() }}</span> 
                        results
                    </div>
                    
                    <div class="pagination-controls">
                        <!-- First page -->
                        @if($users->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-2') }}">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->url(1) }}" class="pagination-button {{ themeClass('button') }}" wire:click.prevent="gotoPage(1)" wire:key="first-page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        <!-- Previous page -->
                        @if($users->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-2') }}">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="pagination-button {{ themeClass('button') }}" wire:click.prevent="previousPage" wire:key="prev-page">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Active page -->
                        @foreach($users->getUrlRange(max($users->currentPage() - 2, 1), min($users->currentPage() + 2, $users->lastPage())) as $page => $url)
                            @if($page == $activities->currentPage())
                                <span class="pagination-button pagination-active {{ themeClass('button-accent') }} cursor-pointer">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="pagination-button {{ themeClass('button') }}" wire:click.prevent="gotoPage({{ $page }})" wire:key="page-{{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                        
                        <!-- Next page -->
                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="pagination-button {{ themeClass('button') }}" wire:click.prevent="nextPage" wire:key="next-page">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 {{ themeClass('text-2') }}">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        <!-- Last page -->
                        @if($users->hasMorePages())
                            <a href="{{ $users->url($users->lastPage()) }}" class="pagination-button {{ themeClass('button') }} " wire:click.prevent="gotoPage({{ $users->lastPage() }})" wire:key="last-page">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 {{ themeClass('text-2') }}">
                                <i class="fas fa-angle-double-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        html, body {
            height: 100%;
            overflow-y: hidden !important;
            margin: 0;
            padding: 0;
        }
        
        .admin-container {
            overflow-y: auto;
            max-height: calc(100vh - var(--nav-height) - var(--footer-height));
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .admin-container::-webkit-scrollbar {
            width: 0;
            display: none;
        }
    </style>

    <script>
        // Implementation to preserve scroll position when changing pages
        document.addEventListener('livewire:init', () => {
            let scrollPosition = 0;
            
            Livewire.hook('message.sent', () => {
                // Save scroll position before loading
                const container = document.querySelector('.activities-container');
                if (container) {
                    scrollPosition = container.scrollTop;
                }
            });
            
            Livewire.hook('message.processed', () => {
                // Restore scroll position after loading
                setTimeout(() => {
                    const container = document.querySelector('.activities-container');
                    if (container) {
                        container.scrollTop = scrollPosition;
                    }
                }, 100);
            });
        });
    </script>
</div>