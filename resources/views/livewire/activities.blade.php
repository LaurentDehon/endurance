<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header optimisé mobile -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">        
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto ml-auto">
            <!-- Search bar -->
            <div class="relative w-full">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search..." 
                       class="w-full h-10 px-4 py-2 text-sm sm:text-base rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 transition-colors">✕</button>
                @endif
            </div>
        
            <!-- Contrôles regroupés -->
            <div class="flex gap-3 h-10">
                <select wire:model.live="perPage" 
                        class="h-full px-4 py-2 text-sm sm:text-base rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="10">10/page</option>
                    <option value="25">25/page</option>
                    <option value="50">50/page</option>
                </select>
        
                <button wire:click.prevent="deleteAll" 
                        class="h-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 text-sm sm:text-base rounded-lg transition-colors whitespace-nowrap">
                    Delete all
                </button>
            </div>
        </div>
    </div>

    <!-- Tableau responsive -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- En-têtes adaptatifs -->
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            <span class="hidden xs:inline">Activité</span>
                            <span class="xs:hidden">Name</span>
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        
                        <!-- Date responsive -->
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('start_date')">
                            <span class="hidden sm:inline">Date</span>
                            <span class="sm:hidden">📅</span>
                        </th>

                        <!-- Colonnes compressées sur mobile -->
                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('distance')">
                            <span class="hidden sm:inline">Distance</span>
                            <span class="sm:hidden">↔</span>
                        </th>

                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('moving_time')">
                            <span class="hidden sm:inline">Time</span>
                            <span class="sm:hidden">⏱</span>
                        </th>

                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('total_elevation_gain')">
                            <span class="hidden sm:inline">Elevation</span>
                            <span class="sm:hidden">⛰</span>
                        </th>

                        <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Nom d'activité tronqué -->
                            <td class="px-4 sm:px-6 py-4 max-w-[150px] sm:max-w-none truncate" title="{{ $activity->name }}">
                                {{ Str::limit($activity->name, $loop->first ? 25 : 15) }}
                            </td>

                            <!-- Date format responsive -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="sm:hidden">{{ $activity->start_date->format('d/m/y') }}</span>
                                <span class="hidden sm:inline">{{ $activity->start_date->format('d/m/Y H:i') }}</span>
                            </td>

                            <!-- Données numériques compactes -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="text-sm sm:text-base">
                                    {{ number_format($activity->distance / 1000, 1) }}<span class="hidden sm:inline">km</span>
                                </span>
                            </td>

                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="text-sm sm:text-base">
                                    {{ formatTimeCompact($activity->moving_time) }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <span class="text-sm sm:text-base">
                                    {{ $activity->total_elevation_gain }}<span class="hidden sm:inline">m</span>
                                </span>
                            </td>

                            <!-- Actions agrandies pour mobile -->
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap space-x-3">
                                <a href="{{ route('activities', $activity) }}" class="text-indigo-600 hover:text-indigo-900 p-1.5 sm:p-1">
                                    <i class="fas fa-eye text-sm sm:text-base"></i>
                                </a>
                                <button wire:click.prevent="delete({{ $activity->id }})" class="text-red-600 hover:text-red-900 p-1.5 sm:p-1">
                                    <i class="fas fa-trash text-sm sm:text-base"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                               No activity found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination adaptative -->
        @if($activities->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200">
                {{ $activities->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>