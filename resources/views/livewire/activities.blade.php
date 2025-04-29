<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 activities-container">
    <div class="flex justify-between items-start mb-6 gap-4">        
        <div class="flex gap-3 w-full ml-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." 
                       class="w-full h-10 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 placeholder-gray-400 focus:ring-0 transition-all border-0 outline-none">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 text-cyan-200 transition-colors" data-tippy-content="Clear search">✕</button>
                @endif
            </div>
        
            <!-- Items per page -->
            <div class="flex gap-3 h-10">
                <div class="relative" x-data="{ open: false, selected: '{{ $perPage }}' }">
                    <button @click="open = !open" type="button" class="flex items-center justify-between w-28 px-4 py-2 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-0 transition-all" data-tippy-content="Select items per page">
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
    </div>
    
    <livewire:modal.confirmation-modal />

    <!-- Table -->
    <div class="backdrop-blur-lg rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto {{ $activities->hasPages() ? 'rounded-t-xl' : 'rounded-xl' }}">
            <table class="min-w-full divide-y divide-cyan-200 divide-opacity-20 bg-white bg-opacity-10">
                <thead>
                    <tr>
                        <!-- Headings -->
                        <th class="w-1/4 px-4 py-3 text-left text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('name')"
                            data-tippy-content="Sort by name">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        
                        <th class="px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('start_date')"
                            data-tippy-content="Sort by date">
                            Date
                            @include('components.sort-icon', ['field' => 'start_date'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('distance')"
                            data-tippy-content="Sort by distance">
                            Distance
                            @include('components.sort-icon', ['field' => 'distance'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('moving_time')"
                            data-tippy-content="Sort by time">
                            Time
                            @include('components.sort-icon', ['field' => 'moving_time'])
                        </th>

                        <th class="hidden md:table-cell px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('total_elevation_gain')"
                            data-tippy-content="Sort by elevation">
                            Elevation
                            @include('components.sort-icon', ['field' => 'total_elevation_gain'])
                        </th>
                        
                        <!-- Nouvelles colonnes pour lg et plus -->
                        <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('average_heartrate')"
                            data-tippy-content="Sort by average heart rate">
                            Average HR
                            @include('components.sort-icon', ['field' => 'average_heartrate'])
                        </th>
                        
                        <th class="hidden lg:table-cell px-4 py-3 text-center text-xs font-medium text-cyan-200 uppercase cursor-pointer" 
                            wire:click="sortBy('average_speed')"
                            data-tippy-content="Sort by average pace">
                            Average Pace
                            @include('components.sort-icon', ['field' => 'average_speed'])
                        </th>
                    </tr>
                </thead>
                
                <tbody>
                    @forelse($activities as $activity)
                        <tr class="{{ $loop->even ? 'bg-white bg-opacity-10' : 'bg-slate-800 bg-opacity-30' }}">
                            <!-- Name -->
                            <td class="w-1/4 px-4 py-4 truncate">
                                <div class="text-sm font-medium text-amber-300">
                                    <a href="https://www.strava.com/activities/{{ $activity->strava_id }}" target="_blank" data-tippy-content="{{ $activity->name }}">
                                        <span class="cursor-pointer hover:underline">{{ Str::limit($activity->name, 25) }}</span>
                                    </a>
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="block text-sm md:hidden text-cyan-200">{{ $activity->start_date->format('d/m/y') }}</span>
                                <span class="hidden md:block text-sm text-cyan-200">{{ $activity->start_date->format('d/m/Y H:i') }}</span>
                            </td>

                            <!-- Distance -->
                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-cyan-200">
                                    {{ number_format($activity->distance / 1000, 1) }}km
                                </span>
                            </td>

                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-cyan-200">
                                    {{ formatTimeCompact($activity->moving_time) }}
                                </span>
                            </td>

                            <!-- Elevation -->
                            <td class="hidden md:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-cyan-200">
                                    {{ $activity->total_elevation_gain }}m
                                </span>
                            </td>

                            <!-- Average Heart Rate -->
                            <td class="hidden lg:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-cyan-200">
                                    {{ $activity->average_heartrate ? round($activity->average_heartrate) : 'N/A' }}
                                </span>
                            </td>

                            <!-- Average Pace -->
                            <td class="hidden lg:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium text-cyan-200">
                                    @if($activity->average_speed > 0)
                                        @php
                                            $secondsPerKm = 1000 / $activity->average_speed;
                                            $minutes = floor($secondsPerKm / 60);
                                            $seconds = floor($secondsPerKm % 60);
                                        @endphp
                                        {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }}/km
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-cyan-200">
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
            <div class="p-4 border-t bg-white bg-opacity-10">
                <div class="custom-pagination">
                    <div class="pagination-summary text-cyan-200">
                        Showing 
                        <span class="font-medium mx-1 text-white">{{ $activities->firstItem() }}</span> 
                        to 
                        <span class="font-medium mx-1 text-white">{{ $activities->lastItem() }}</span> 
                        of 
                        <span class="font-medium mx-1 text-white">{{ $activities->total() }}</span> 
                        results
                    </div>
                    
                    <div class="pagination-controls">
                        <!-- First page -->
                        @if($activities->onFirstPage())
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $activities->url(1) }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="gotoPage(1)" wire:key="first-page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        <!-- Previous page -->
                        @if($activities->onFirstPage())
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $activities->previousPageUrl() }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="previousPage" wire:key="prev-page">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Active page -->
                        @foreach($activities->getUrlRange(max($activities->currentPage() - 2, 1), min($activities->currentPage() + 2, $activities->lastPage())) as $page => $url)
                            @if($page == $activities->currentPage())
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
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->nextPageUrl() }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500" wire:click.prevent="nextPage" wire:key="next-page">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 text-cyan-200">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        <!-- Last page -->
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->url($activities->lastPage()) }}" class="pagination-button text-white bg-cyan-600 hover:bg-cyan-500 " wire:click.prevent="gotoPage({{ $activities->lastPage() }})" wire:key="last-page">
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
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        
        .activities-container {
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        
        .activities-container::-webkit-scrollbar {
            width: 0;
            display: none;
        }        
        
        /* Tippy.js theme customization for activities */
        .tippy-box[data-theme~='activities'] {
            background-color: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e2e8f0;
            backdrop-filter: blur(12px);
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .tippy-box[data-theme~='activities'] .tippy-arrow {
            color: rgba(15, 23, 42, 0.9);
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
        
        // Initialize Tippy.js tooltips for activities
        function initActivityTooltips() {
            tippy('[data-tippy-content]', {
                theme: 'activities',
                allowHTML: true,
                arrow: true,
                placement: 'top',
                duration: [200, 100],
                delay: [100, 0]
            });
        }
        
        // Initialize tooltips on page load and after Livewire updates
        document.addEventListener('livewire:navigated', () => initActivityTooltips());
        document.addEventListener('livewire:init', () => initActivityTooltips());
        document.addEventListener('DOMContentLoaded', () => initActivityTooltips());
        
        // Re-initialize tooltips after Livewire updates
        Livewire.hook('message.processed', () => {
            setTimeout(() => initActivityTooltips(), 100);
        });
    </script>
</div>