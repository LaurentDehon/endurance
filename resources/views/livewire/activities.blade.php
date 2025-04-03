<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[calc(100vh-var(--nav-height)-var(--footer-height,0px))] activities-container">
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
            scrollbar-width: none; /* Masque la scrollbar sur Firefox */
            -ms-overflow-style: none; /* Masque la scrollbar sur IE/Edge */
        }
        
        .activities-container::-webkit-scrollbar {
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

    <!-- Header optimisé mobile -->
    <div class="flex justify-between items-start mb-6 gap-4">        
        <div class="flex gap-3 w-full ml-auto">
            <!-- Search bar -->
            <div class="relative flex-1">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search..." 
                       class="w-full h-10 px-4 py-2 rounded-lg {{ themeClass('input-bg', 'bg-gray-700 bg-opacity-40') }} {{ themeClass('text-primary', 'text-gray-200') }} border {{ themeClass('input-border', 'border-gray-600') }} focus:ring-2 focus:ring-blue-500 placeholder-gray-400 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 {{ themeClass('text-secondary', 'text-gray-400 hover:text-gray-300') }} transition-colors">✕</button>
                @endif
            </div>
        
            <!-- Contrôles regroupés -->
            <div class="flex gap-3 h-10">
                <select wire:model.live="perPage" 
                        class="w-28 px-4 py-2 rounded-lg {{ themeClass('input-bg', 'bg-gray-700 bg-opacity-40') }} {{ themeClass('text-primary', 'text-gray-200') }} border {{ themeClass('input-border', 'border-gray-600') }} focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="10">10/page</option>
                    <option value="25">25/page</option>
                    <option value="50">50/page</option>
                </select>
        
                <button wire:click.prevent="deleteAll" 
                        class="h-full {{ themeClass('button-bg', 'bg-red-500 hover:bg-red-600') }} {{ themeClass('button-text', 'text-white') }} px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
                    Delete all
                </button>
            </div>
        </div>
    </div>

    <!-- Tableau non-responsive -->
    <div class="{{ themeClass('card-bg', 'bg-white bg-opacity-10') }} backdrop-blur-lg rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y {{ themeClass('table-divider', 'divide-gray-600') }}">
                <thead class="{{ themeClass('thead-bg', 'bg-gray-700 bg-opacity-40') }}">
                    <tr>
                        <!-- En-têtes standards -->
                        <th class="w-1/4 px-4 py-3 text-left text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        
                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('start_date')">
                            Date
                            @include('components.sort-icon', ['field' => 'start_date'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('distance')">
                            Distance
                            @include('components.sort-icon', ['field' => 'distance'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('moving_time')">
                            Time
                            @include('components.sort-icon', ['field' => 'moving_time'])
                        </th>

                        <th class="hidden md:table-cell px-4 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase cursor-pointer" 
                            wire:click="sortBy('total_elevation_gain')">
                            Elevation
                            @include('components.sort-icon', ['field' => 'total_elevation_gain'])
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-medium {{ themeClass('text-secondary', 'text-gray-300') }} uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="{{ themeClass('tbody-bg', 'bg-gray-800 bg-opacity-40') }} {{ themeClass('tbody-divider', 'divide-y divide-gray-600') }}">
                    @forelse($activities as $activity)
                        <tr class="{{ themeClass('tr-hover', 'hover:bg-gray-800 hover:bg-opacity-60') }} transition-colors">
                            <!-- Nom d'activité standard -->
                            <td class="w-1/4 px-4 py-4 truncate" title="{{ $activity->name }}">
                                <div class="text-sm font-medium {{ themeClass('text-primary', 'text-gray-200') }}">
                                    {{ Str::limit($activity->name, 25) }}
                                </div>
                            </td>

                            <!-- Date format responsive -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="block text-sm md:hidden {{ themeClass('text-primary', 'text-gray-200') }}">{{ $activity->start_date->format('d/m/y') }}</span>
                                <span class="hidden md:block text-sm {{ themeClass('text-primary', 'text-gray-200') }}">{{ $activity->start_date->format('d/m/Y H:i') }}</span>
                            </td>

                            <!-- Distance - hidden on smallest screens -->
                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium {{ themeClass('text-primary', 'text-gray-200') }}">
                                    {{ number_format($activity->distance / 1000, 1) }}km
                                </span>
                            </td>

                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium {{ themeClass('text-primary', 'text-gray-200') }}">
                                    {{ formatTimeCompact($activity->moving_time) }}
                                </span>
                            </td>

                            <!-- Elevation - hidden on small/medium screens -->
                            <td class="hidden md:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium {{ themeClass('text-primary', 'text-gray-200') }}">
                                    {{ $activity->total_elevation_gain }}m
                                </span>
                            </td>

                            <!-- Actions standards -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center gap-3">
                                    <a href="https://www.strava.com/activities/{{ $activity->strava_id }}" target="_blank" 
                                       class="{{ themeClass('link-color', 'text-blue-400 hover:text-blue-300') }}">
                                        <i class="fas fa-eye text-base"></i>
                                    </a>
                                    <button wire:click.prevent="delete({{ $activity->id }})" 
                                            class="{{ themeClass('danger-color', 'text-red-400 hover:text-red-300') }}">
                                        <i class="fas fa-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center {{ themeClass('text-secondary', 'text-gray-400') }}">
                               <div class="flex justify-center items-center py-6">
                                    <span class="font-medium">No activity found</span>
                               </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination avec nouveau design -->
        @if($activities->hasPages())
            <div class="px-6 py-6 border-t {{ themeClass('border-divider', 'border-gray-700') }}">
                <div class="custom-pagination">
                    <!-- Résumé de pagination -->
                    <div class="pagination-summary {{ themeClass('text-secondary', 'text-gray-300') }}">
                        Showing 
                        <span class="font-medium mx-1 {{ themeClass('text-primary', 'text-gray-200') }}">{{ $activities->firstItem() }}</span> 
                        to 
                        <span class="font-medium mx-1 {{ themeClass('text-primary', 'text-gray-200') }}">{{ $activities->lastItem() }}</span> 
                        of 
                        <span class="font-medium mx-1 {{ themeClass('text-primary', 'text-gray-200') }}">{{ $activities->total() }}</span> 
                        results
                    </div>
                    
                    <!-- Contrôles de pagination -->
                    <div class="pagination-controls">
                        <!-- Bouton première page -->
                        @if($activities->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-secondary', 'text-gray-500') }}">
                                <i class="fas fa-angle-double-left"></i>
                            </span>
                        @else
                            <a href="{{ $activities->url(1) }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="gotoPage(1)" wire:key="first-page">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                        @endif

                        <!-- Bouton précédent -->
                        @if($activities->onFirstPage())
                            <span class="pagination-button opacity-50 {{ themeClass('text-secondary', 'text-gray-500') }}">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $activities->previousPageUrl() }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="previousPage" wire:key="prev-page">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif
                        
                        <!-- Numéros de page -->
                        @foreach($activities->getUrlRange(max($activities->currentPage() - 2, 1), min($activities->currentPage() + 2, $activities->lastPage())) as $page => $url)
                            @if($page == $activities->currentPage())
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
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->nextPageUrl() }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="nextPage" wire:key="next-page">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="pagination-button opacity-50 {{ themeClass('text-secondary', 'text-gray-500') }}">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif

                        <!-- Bouton dernière page -->
                        @if($activities->hasMorePages())
                            <a href="{{ $activities->url($activities->lastPage()) }}" class="pagination-button {{ themeClass('button-bg', 'bg-gray-700 bg-opacity-40 hover:bg-gray-600') }} {{ themeClass('text-primary', 'text-gray-200') }}" wire:click.prevent="gotoPage({{ $activities->lastPage() }})" wire:key="last-page">
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
    
    <script>
        // Implémentation pour préserver la position de défilement lors du changement de page
        document.addEventListener('livewire:init', () => {
            let scrollPosition = 0;
            
            Livewire.hook('message.sent', () => {
                // Enregistrer la position de défilement avant le chargement
                const container = document.querySelector('.activities-container');
                if (container) {
                    scrollPosition = container.scrollTop;
                }
            });
            
            Livewire.hook('message.processed', () => {
                // Restaurer la position de défilement après le chargement
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