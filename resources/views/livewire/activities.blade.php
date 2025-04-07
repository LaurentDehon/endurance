<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))] activities-container">
    <div class="flex justify-between items-start mb-6 gap-4">        
        <div class="flex gap-3 w-full ml-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." 
                       class="w-full h-10 px-4 py-2 rounded-lg {{ themeClass('input') }} placeholder-gray-400 focus:ring-0 transition-all border-0 outline-none">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 {{ themeClass('text-2') }} transition-colors">✕</button>
                @endif
            </div>
        
            <!-- Items per page -->
            <div class="flex gap-3 h-10">
                <div class="relative" x-data="{ open: false, selected: '{{ $perPage }}' }">
                    <button @click="open = !open" type="button" class="flex items-center justify-between w-28 px-4 py-2 rounded-lg {{ themeClass('input') }} focus:ring-0 transition-all">
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
                        class="absolute right-0 mt-1 w-28 z-10 rounded-lg overflow-hidden shadow-lg {{ themeClass('input') }} backdrop-blur-sm">
                        <div class="py-1">
                            <button wire:click="$set('perPage', 10)" @click="open = false; selected='10'" class="w-full text-left px-4 py-2 text-sm {{ themeClass('text-1') }} hover:bg-opacity-20 hover:bg-white transition-colors">10/page</button>
                            <button wire:click="$set('perPage', 25)" @click="open = false; selected='25'" class="w-full text-left px-4 py-2 text-sm {{ themeClass('text-1') }} hover:bg-opacity-20 hover:bg-white transition-colors">25/page</button>
                            <button wire:click="$set('perPage', 50)" @click="open = false; selected='50'" class="w-full text-left px-4 py-2 text-sm {{ themeClass('text-1') }} hover:bg-opacity-20 hover:bg-white transition-colors">50/page</button>
                        </div>
                    </div>
                </div>
        
                <button wire:click.prevent="deleteAll" 
                        class="h-full {{ themeClass('button-danger') }} px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
                    Delete all
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="backdrop-blur-lg rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto border rounded-t-xl {{ themeClass('table-border') }}">
            <table class="min-w-full divide-y {{ themeClass('table') }}">
                <thead>
                    <tr>
                        <!-- Headings -->
                        <th class="w-1/4 px-4 py-3 text-left text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('start_date')">
                            Date
                            @include('components.sort-icon', ['field' => 'start_date'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('distance')">
                            Distance
                            @include('components.sort-icon', ['field' => 'distance'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('moving_time')">
                            Time
                            @include('components.sort-icon', ['field' => 'moving_time'])
                        </th>

                        <th class="hidden md:table-cell px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase cursor-pointer" 
                            wire:click="sortBy('total_elevation_gain')">
                            Elevation
                            @include('components.sort-icon', ['field' => 'total_elevation_gain'])
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-2') }} uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($activities as $activity)
                        <tr class="{{ $loop->even ? themeClass('table-even') : themeClass('table-odd') }}">
                            <!-- Name -->
                            <td class="w-1/4 px-4 py-4 truncate" title="{{ $activity->name }}">
                                <div class="text-sm font-medium {{ themeClass('text-2') }}">
                                    <a href="https://www.strava.com/activities/{{ $activity->strava_id }}" target="_blank" >
                                        <span class="cursor-pointer underline">{{ Str::limit($activity->name, 25) }}</span>
                                    </a>
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="block text-sm md:hidden {{ themeClass('text-2') }}">{{ $activity->start_date->format('d/m/y') }}</span>
                                <span class="hidden md:block text-sm {{ themeClass('text-2') }}">{{ $activity->start_date->format('d/m/Y H:i') }}</span>
                            </td>

                            <!-- Distance -->
                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium {{ themeClass('text-2') }}">
                                    {{ number_format($activity->distance / 1000, 1) }}km
                                </span>
                            </td>

                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium {{ themeClass('text-2') }}">
                                    {{ formatTimeCompact($activity->moving_time) }}
                                </span>
                            </td>

                            <!-- Elevation -->
                            <td class="hidden md:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium {{ themeClass('text-2') }}">
                                    {{ $activity->total_elevation_gain }}m
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center gap-3">
                                    <button wire:click.prevent="delete({{ $activity->id }})" 
                                            class="text-red-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center {{ themeClass('text-2') }}">
                               <div class="flex justify-center items-center py-6">
                                    <span class="font-medium">No activity found</span>
                               </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="p-4 border-t {{ themeClass('table') }}">
                <div class="custom-pagination">
                    <div class="pagination-summary {{ themeClass('text-2') }}">
                        Showing 
                        <span class="font-medium mx-1 {{ themeClass('text-1') }}">{{ $activities->firstItem() }}</span> 
                        to 
                        <span class="font-medium mx-1 {{ themeClass('text-1') }}">{{ $activities->lastItem() }}</span> 
                        of 
                        <span class="font-medium mx-1 {{ themeClass('text-1') }}">{{ $activities->total() }}</span> 
                        results
                    </div>
                    
                    <div class="pagination-controls">
                        <!-- First page -->
                        @if($activities->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-2') }}">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $activities->url(1) }}" class="pagination-button {{ themeClass('button') }}" wire:click.prevent="gotoPage(1)" wire:key="first-page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        <!-- Previous page -->
                        @if($activities->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-2') }}">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $activities->previousPageUrl() }}" class="pagination-button {{ themeClass('button') }}" wire:click.prevent="previousPage" wire:key="prev-page">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Active page -->
                        @foreach($activities->getUrlRange(max($activities->currentPage() - 2, 1), min($activities->currentPage() + 2, $activities->lastPage())) as $page => $url)
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
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->nextPageUrl() }}" class="pagination-button {{ themeClass('button') }}" wire:click.prevent="nextPage" wire:key="next-page">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 {{ themeClass('text-2') }}">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        <!-- Last page -->
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->url($activities->lastPage()) }}" class="pagination-button {{ themeClass('button') }} " wire:click.prevent="gotoPage({{ $activities->lastPage() }})" wire:key="last-page">
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
        
        .activities-container {
            overflow-y: auto;
            max-height: calc(100vh - var(--nav-height) - var(--footer-height));
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .activities-container::-webkit-scrollbar {
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