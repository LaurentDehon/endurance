<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header optimisé mobile -->
    <div class="flex justify-between items-start mb-6 gap-4">        
        <div class="flex gap-3 w-full ml-auto">
            <!-- Search bar -->
            <div class="relative flex-1">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search..." 
                       class="w-full h-10 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 transition-colors">✕</button>
                @endif
            </div>
        
            <!-- Contrôles regroupés -->
            <div class="flex gap-3 h-10">
                <select wire:model.live="perPage" class="w-28 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="10">10/page</option>
                    <option value="25">25/page</option>
                    <option value="50">50/page</option>
                </select>
        
                <button wire:click.prevent="deleteAll" 
                        class="h-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors whitespace-nowrap">
                    Delete all
                </button>
            </div>
        </div>
    </div>

    <!-- Tableau non-responsive -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- En-têtes standards -->
                        <th class="w-1/4 px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Name
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('start_date')">
                            Date
                            @include('components.sort-icon', ['field' => 'start_date'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('distance')">
                            Distance
                            @include('components.sort-icon', ['field' => 'distance'])
                        </th>

                        <th class="hidden sm:table-cell px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('moving_time')">
                            Time
                            @include('components.sort-icon', ['field' => 'moving_time'])
                        </th>

                        <th class="hidden md:table-cell px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('total_elevation_gain')">
                            Elevation
                            @include('components.sort-icon', ['field' => 'total_elevation_gain'])
                        </th>

                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Nom d'activité standard -->
                            <td class="w-1/4 px-4 py-4 truncate" title="{{ $activity->name }}">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ Str::limit($activity->name, 25) }}
                                </div>
                            </td>

                            <!-- Date format responsive -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <span class="block text-sm md:hidden">{{ $activity->start_date->format('d/m/y') }}</span>
                                <span class="hidden md:block text-sm">{{ $activity->start_date->format('d/m/Y H:i') }}</span>
                            </td>

                            <!-- Distance - hidden on smallest screens -->
                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium">
                                    {{ number_format($activity->distance / 1000, 1) }}km
                                </span>
                            </td>

                            <td class="hidden sm:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium">
                                    {{ formatTimeCompact($activity->moving_time) }}
                                </span>
                            </td>

                            <!-- Elevation - hidden on small/medium screens -->
                            <td class="hidden md:table-cell px-4 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium">
                                    {{ $activity->total_elevation_gain }}m
                                </span>
                            </td>

                            <!-- Actions standards -->
                            <td class="px-4 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center items-center space-x-1">
                                    <a href="https://www.strava.com/activities/{{ $activity->strava_id }}" target="_blank" 
                                       class="text-indigo-600 hover:text-indigo-900 p-1 rounded-full hover:bg-indigo-100 transition-colors">
                                        <i class="fas fa-eye text-base"></i>
                                    </a>
                                    <button wire:click.prevent="delete({{ $activity->id }})" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded-full hover:bg-red-100 transition-colors">
                                        <i class="fas fa-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                               <div class="flex justify-center items-center py-6">
                                    <span class="font-medium">No activity found</span>
                               </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination standard -->
        @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->onEachSide(1)->links() }}
            </div>
        @endif
    </div>
</div>