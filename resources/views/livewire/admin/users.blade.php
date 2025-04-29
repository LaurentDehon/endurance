<div class="container max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 admin-container">
    <!-- Navigation tabs -->
    <div class="flex mb-6 gap-4 text-center">
        <a href="{{ route('admin.users') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-amber-600 hover:bg-amber-500 transition-colors">
            <i class="fas fa-users mr-2"></i> Users
        </a>
        <a href="{{ route('admin.visits') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
            <i class="fas fa-chart-line mr-2"></i> Visits
        </a>
    </div>

    <div class="flex justify-between items-center mb-6 gap-4">        
        <div class="flex gap-4 w-full ml-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." 
                       class="w-full h-10 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400 focus:ring-0 transition-all border-0 outline-none">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 text-cyan-200 transition-colors">✕</button>
                @endif
            </div>

            <!-- Items per page -->
            <div class="relative" x-data="{ open: false, selected: '{{ $perPage }}' }">
                <button @click="open = !open" type="button" class="flex items-center justify-between w-28 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-0 transition-all">
                    <span x-text="selected + '/page'"></span>
                    <i class="fas fa-chevron-down text-xs ml-1 opacity-70 transition-transform duration-200" :class="{'rotate-180': open}"></i>
                </button>
                
                <div x-show="open" @click.away="open = false" x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-1 w-28 z-10 rounded-lg overflow-hidden shadow-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 backdrop-blur-sm">
                    <div class="py-1">
                        <button wire:click="$set('perPage', 10)" @click="open = false; selected='10'" class="w-full text-left px-4 py-2 text-sm text-white hover:bg-opacity-20 hover:bg-white transition-colors">10/page</button>
                        <button wire:click="$set('perPage', 25)" @click="open = false; selected='25'" class="w-full text-left px-4 py-2 text-sm text-white hover:bg-opacity-20 hover:bg-white transition-colors">25/page</button>
                        <button wire:click="$set('perPage', 50)" @click="open = false; selected='50'" class="w-full text-left px-4 py-2 text-sm text-white hover:bg-opacity-20 hover:bg-white transition-colors">50/page</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="backdrop-blur-lg rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto {{ $users->hasPages() ? 'rounded-t-xl' : 'rounded-xl' }}">
            <table class="min-w-full divide-y divide-cyan-200 divide-opacity-20 bg-white bg-opacity-10">
                <thead>
                    <tr>
                        <!-- Headings -->
                        <th class="px-4 py-3 text-left text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-cyan-200 uppercase cursor-pointer hidden sm:table-cell" 
                            wire:click="sortBy('email')">
                            Email
                            @include('components.sort-icon', ['field' => 'email'])
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase">
                            Verified
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase">
                            Admin
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('last_login_at')">
                            Last Login
                            @include('components.sort-icon', ['field' => 'last_login_at'])
                        </th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($users as $user)
                        <tr class="{{ $loop->even ? 'bg-white bg-opacity-10' : 'bg-slate-800 bg-opacity-30' }}">
                            <!-- Name with tooltip for long names -->
                            <td class="px-4 py-4 max-w-[150px] sm:max-w-none truncate">
                                <a href="{{ route('user.detail', $user->id) }}" class="text-sm font-medium hover:underline text-amber-300" title="{{ $user->name }}">
                                    {{ $user->name }}
                                </a>
                                <div class="text-xs text-cyan-200 sm:hidden truncate">
                                    {{ $user->email }}
                                </div>
                            </td>
                            
                            <!-- Email -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm hidden sm:table-cell">
                                <div class="text-cyan-200">{{ $user->email }}</div>
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
                                        <div class="text-cyan-200">{{ $lastLoginDate ? $lastLoginDate->diffForHumans() : '-' }}</div>
                                        @if($lastLoginDate)
                                            <div class="text-xs text-gray-400 mt-1">{{ $lastLoginDate->format('d/m/Y H:i') }}</div>
                                        @endif
                                    </span>
                                @else
                                <div class="text-cyan-200">-</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-cyan-200">
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
            <div class="p-4 border-t bg-white bg-opacity-10">
                <div class="custom-pagination">
                    <div class="pagination-summary text-cyan-200">
                        Showing 
                        <span class="font-medium mx-1 text-white">{{ $users->firstItem() }}</span> 
                        to 
                        <span class="font-medium mx-1 text-white">{{ $users->lastItem() }}</span> 
                        of 
                        <span class="font-medium mx-1 text-white">{{ $users->total() }}</span> 
                        results
                    </div>
                    
                    <div class="pagination-controls">
                        <!-- First page -->
                        @if($users->onFirstPage())
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->url(1) }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="gotoPage(1)" wire:key="first-page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        <!-- Previous page -->
                        @if($users->onFirstPage())
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="previousPage" wire:key="prev-page">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Active page -->
                        @foreach($users->getUrlRange(max($users->currentPage() - 2, 1), min($users->currentPage() + 2, $users->lastPage())) as $page => $url)
                            @if($page == $users->currentPage())
                                <span class="pagination-button pagination-active bg-amber-600 text-white hover:bg-amber-500 cursor-pointer">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="gotoPage({{ $page }})" wire:key="page-{{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                        
                        <!-- Next page -->
                        @if($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="nextPage" wire:key="next-page">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        <!-- Last page -->
                        @if($users->hasMorePages())
                            <a href="{{ $users->url($users->lastPage()) }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500 " wire:click.prevent="gotoPage({{ $users->lastPage() }})" wire:key="last-page">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-angle-double-right"></i>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .pagination-button {
            @apply inline-flex items-center justify-center w-8 h-8 rounded-md mx-1;
        }
    </style>

    <!-- Confirmation Modal Component -->
    <livewire:modal.confirmation-modal />
</div>