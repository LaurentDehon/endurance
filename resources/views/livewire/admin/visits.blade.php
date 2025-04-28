<div class="container max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 admin-container">
    <!-- Navigation tabs -->
    <div class="flex mb-6 gap-4 text-center">
        <a href="{{ route('admin.users') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-cyan-600 hover:bg-cyan-500 transition-colors">
            <i class="fas fa-users mr-2"></i> Users
        </a>
        <a href="{{ route('admin.visits') }}" class="flex-1 px-5 py-3 rounded-lg text-white bg-amber-600 hover:bg-amber-500 transition-colors">
            <i class="fas fa-chart-line mr-2"></i> Visits
        </a>
    </div>

    <div class="flex justify-between items-center mb-6 gap-4">
        <div class="flex gap-4 w-full ml-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search IP or country..." 
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
        <div class="overflow-x-auto {{ $visits->hasPages() ? 'rounded-t-xl' : 'rounded-xl' }}">
            <table class="min-w-full divide-y divide-cyan-200 divide-opacity-20 bg-white bg-opacity-10">
                <thead>
                    <tr>
                        <!-- IP Address -->
                        <th class="px-4 py-3 text-left text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('ip_address')">
                            IP Address
                            @include('components.sort-icon', ['field' => 'ip_address'])
                        </th>
                        
                        <!-- Country -->
                        <th class="px-4 py-3 text-left text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('country')">
                            Country
                            @include('components.sort-icon', ['field' => 'country'])
                        </th>

                        <!-- Visit Count -->
                        <th class="px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer"
                            wire:click="sortBy('total_visits')">
                            Total Visits
                            @include('components.sort-icon', ['field' => 'total_visits'])
                        </th>
                        
                        <!-- Last Visit -->
                        <th class="px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('last_visit')">
                            Last Visit
                            @include('components.sort-icon', ['field' => 'last_visit'])
                        </th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($visits as $visit)
                        <tr class="{{ $loop->even ? 'bg-white bg-opacity-10' : 'bg-slate-800 bg-opacity-30' }}">
                            <!-- IP Address -->
                            <td class="px-4 py-4 max-w-[150px] sm:max-w-none">
                                <div class="text-sm font-medium text-amber-300">
                                    {{ $visit->ip_address }}
                                </div>
                            </td>
                            
                            <!-- Country -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm">
                                <div class="text-cyan-200">
                                    {{ $visit->country ?? 'Unknown' }}
                                </div>
                            </td>
                            
                            <!-- Visit Count -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                <span class="inline-flex items-center px-2.5 py-1.5 rounded-full text-xs font-medium bg-blue-300 text-blue-800">
                                    {{ $visit->total_visits }}
                                </span>
                            </td>
                            
                            <!-- Last Visit -->
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-center">
                                @php
                                    try {
                                        $lastVisitDate = \Carbon\Carbon::parse($visit->last_visit);
                                    } catch (\Exception $e) {
                                        $lastVisitDate = null;
                                    }
                                @endphp
                                
                                <span title="{{ $lastVisitDate ? $lastVisitDate->format('d/m/Y H:i:s') : '' }}">
                                    <div class="text-cyan-200">{{ $lastVisitDate ? $lastVisitDate->diffForHumans() : '-' }}</div>
                                    @if($lastVisitDate)
                                        <div class="text-xs text-gray-400 mt-1">{{ $lastVisitDate->format('d/m/Y H:i') }}</div>
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-cyan-200">
                           <div class="flex justify-center items-center py-6">
                                <span class="font-medium">No visits recorded</span>
                           </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($visits->hasPages())
            <div class="p-4 border-t bg-white bg-opacity-10">
                <div class="custom-pagination">
                    <div class="pagination-summary text-cyan-200">
                        Showing 
                        <span class="font-medium mx-1 text-white">{{ $visits->firstItem() }}</span> 
                        to 
                        <span class="font-medium mx-1 text-white">{{ $visits->lastItem() }}</span> 
                        of 
                        <span class="font-medium mx-1 text-white">{{ $visits->total() }}</span> 
                        results
                    </div>
                    
                    <div class="pagination-controls">
                        <!-- First page -->
                        @if($visits->onFirstPage())
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $visits->url(1) }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="gotoPage(1)" wire:key="first-page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        <!-- Previous page -->
                        @if($visits->onFirstPage())
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $visits->previousPageUrl() }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="previousPage" wire:key="prev-page">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Active page -->
                        @foreach($visits->getUrlRange(max($visits->currentPage() - 2, 1), min($visits->currentPage() + 2, $visits->lastPage())) as $page => $url)
                            @if($page == $visits->currentPage())
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
                        @if($visits->hasMorePages())
                            <a href="{{ $visits->nextPageUrl() }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="nextPage" wire:key="next-page">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        <!-- Last page -->
                        @if($visits->hasMorePages())
                            <a href="{{ $visits->url($visits->lastPage()) }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500 " wire:click.prevent="gotoPage({{ $visits->lastPage() }})" wire:key="last-page">
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
</div>