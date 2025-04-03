<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))] admin-container">
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
            scrollbar-width: none; /* Masque la scrollbar sur Firefox */
            -ms-overflow-style: none; /* Masque la scrollbar sur IE/Edge */
        }
        
        .admin-container::-webkit-scrollbar {
            width: 0;
            display: none; /* Masque la scrollbar sur Chrome, Safari et Opera */
        }

        /* Style de pagination personnalisé amélioré */
        .custom-pagination {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }

        .custom-pagination .pagination-summary {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
        }

        .custom-pagination .pagination-controls {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .custom-pagination .pagination-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.25rem;
            height: 2.25rem;
            padding: 0 0.5rem;
            margin: 0 0.125rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .custom-pagination .pagination-button svg {
            width: 1rem;
            height: 1rem;
        }

        .custom-pagination .pagination-separator {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.25rem;
            height: 2.25rem;
        }

        .custom-pagination .pagination-active {
            font-weight: 600;
            transform: scale(1.05);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
    </style>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 gap-4">        
        <div class="flex gap-4 w-full ml-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Search users..." 
                       class="w-full px-4 py-2 rounded-lg {{ themeClass('input-bg', 'bg-gray-700 bg-opacity-40') }} {{ themeClass('text-primary', 'text-gray-200') }} border {{ themeClass('input-border', 'border-gray-600') }} focus:ring-2 focus:ring-blue-500 placeholder-gray-400 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" 
                            class="absolute right-3 top-2.5 {{ themeClass('text-secondary', 'text-gray-400 hover:text-gray-300') }} transition-colors">
                        ✕
                    </button>
                @endif
            </div>

            <!-- Items per page -->
            <select wire:model.live="perPage" class="w-28 px-4 py-2 rounded-lg {{ themeClass('input-bg', 'bg-gray-700 bg-opacity-40') }} {{ themeClass('text-primary', 'text-gray-200') }} border {{ themeClass('input-border', 'border-gray-600') }} focus:ring-2 focus:ring-blue-500 transition-all">
                <option value="10">10/page</option>
                <option value="25">25/page</option>
                <option value="50">50/page</option>
            </select>
        </div>
    </div>

    <!-- Table with improved responsiveness -->
    <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y {{ themeClass('table-divider', 'divide-gray-600') }}">
                <thead class="{{ themeClass('thead-bg', 'bg-gray-700 bg-opacity-40') }}">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer hidden sm:table-cell" 
                            wire:click="sortBy('email')">
                            Email
                            @include('components.sort-icon', ['field' => 'email'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('activities_count')">
                            Activities
                            @include('components.sort-icon', ['field' => 'activities_count'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('trainings_count')">
                            Trainings
                            @include('components.sort-icon', ['field' => 'trainings_count'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase">
                            Verified
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase">
                            Admin
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('last_login_at')">
                            Last Login
                            @include('components.sort-icon', ['field' => 'last_login_at'])
                        </th>
                        <th class="px-4 sm:px-6 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="{{ themeClass('tbody-bg', 'bg-gray-800 bg-opacity-40') }} {{ themeClass('tbody-divider', 'divide-y divide-gray-600') }}">
                    @forelse($users as $user)
                        <tr class="{{ themeClass('tr-hover', 'hover:bg-gray-800 hover:bg-opacity-60') }} transition-colors">
                            <!-- Name with tooltip for long names -->
                            <td class="px-4 sm:px-6 py-4 max-w-[150px] sm:max-w-none truncate">
                                <div class="text-sm font-medium {{ themeClass('text-primary', 'text-gray-200') }}" title="{{ $user->name }}">
                                    {{ $user->name }}
                                </div>
                                <div class="text-xs {{ themeClass('text-secondary', 'text-gray-400') }} sm:hidden truncate">
                                    {{ $user->email }}
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm hidden sm:table-cell">
                                <div class="{{ themeClass('text-primary', 'text-gray-200') }}">{{ $user->email }}</div>
                            </td>
                            
                            <!-- Activities Count -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="{{ themeClass('text-primary', 'text-gray-200') }}">{{ $user->activities_count ?? 0 }}</div>
                            </td>
                            
                            <!-- Trainings Count -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="{{ themeClass('text-primary', 'text-gray-200') }}">{{ $user->trainings_count ?? 0 }}</div>
                            </td>
                            
                            <!-- Verified with better visual indicators -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
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
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-sm text-center">
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
                                        <div class="{{ themeClass('text-primary', 'text-gray-200') }}">{{ $lastLoginDate ? $lastLoginDate->diffForHumans() : '-' }}</div>
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            
                            <!-- Actions optimized for touch -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex justify-center items-center gap-3">
                                    @if($user->email_verified_at)
                                        <button wire:click="verifyEmail({{ $user->id }})" 
                                                class="text-red-400 hover:text-red-300"
                                                title="Unverify Email">
                                            <i class="fas fa-times-circle text-sm sm:text-base"></i>
                                        </button>
                                    @else
                                        <button wire:click="verifyEmail({{ $user->id }})" 
                                                class="text-green-400 hover:text-green-300"
                                                title="Verify Email">
                                            <i class="fas fa-check-circle text-sm sm:text-base"></i>
                                        </button>
                                    @endif
                                    @if($user->is_admin)
                                        <button wire:click="toggleAdmin({{ $user->id }})" 
                                                class="text-red-400 hover:text-red-300"
                                                title="Revoke Admin">
                                            <i class="fas fa-user-minus text-sm sm:text-base"></i>
                                        </button>
                                    @else
                                        <button wire:click="toggleAdmin({{ $user->id }})" 
                                                class="text-blue-400 hover:text-blue-300"
                                                title="Make Admin">
                                            <i class="fas fa-user-plus text-sm sm:text-base"></i>
                                        </button>
                                    @endif
                                    <button wire:click="resendVerificationEmail({{ $user->id }})" 
                                            class="text-yellow-400 hover:text-yellow-300"
                                            title="Resend Verification Email">
                                        <i class="fas fa-envelope text-sm sm:text-base"></i>
                                    </button>
                                    <button wire:click="sendResetPassword({{ $user->id }})" 
                                            class="text-purple-400 hover:text-purple-300"
                                            title="Send Password Reset">
                                        <i class="fas fa-key text-sm sm:text-base"></i>
                                    </button>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                            class="text-red-400 hover:text-red-300"
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
            <div class="px-6 py-6 border-t {{ themeClass('border-divider', 'border-gray-700') }}">
                <div class="custom-pagination">
                    <!-- Résumé de pagination -->
                    <div class="pagination-summary {{ themeClass('text-secondary', 'text-gray-300') }}">
                        Showing 
                        <span class="font-medium mx-1 {{ themeClass('text-primary', 'text-gray-200') }}">{{ $users->firstItem() }}</span> 
                        to 
                        <span class="font-medium mx-1 {{ themeClass('text-primary', 'text-gray-200') }}">{{ $users->lastItem() }}</span> 
                        of 
                        <span class="font-medium mx-1 {{ themeClass('text-primary', 'text-gray-200') }}">{{ $users->total() }}</span> 
                        results
                    </div>
                    
                    <!-- Contrôles de pagination -->
                    <div class="pagination-controls">
                        <!-- Bouton première page -->
                        @if($users->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-secondary', 'text-gray-500') }}">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->url(1) }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="gotoPage(1)" wire:key="first-page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        <!-- Bouton précédent -->
                        @if($users->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-secondary', 'text-gray-500') }}">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="previousPage" wire:key="prev-page">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Numéros de page -->
                        @foreach($users->getUrlRange(max($users->currentPage() - 2, 1), min($users->currentPage() + 2, $users->lastPage())) as $page => $url)
                            @if($page == $users->currentPage())
                                <span class="pagination-button pagination-active {{ themeClass('active-page', 'bg-blue-600') }} {{ themeClass('text-primary', 'text-white') }}">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="gotoPage({{ $page }})" wire:key="page-{{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                        
                        <!-- Bouton suivant -->
                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="nextPage" wire:key="next-page">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 {{ themeClass('text-secondary', 'text-gray-500') }}">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        <!-- Bouton dernière page -->
                        @if($users->hasMorePages())
                            <a href="{{ $users->url($users->lastPage()) }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="gotoPage({{ $users->lastPage() }})" wire:key="last-page">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 {{ themeClass('text-secondary', 'text-gray-500') }}">
                                <i class="fas fa-angle-double-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>