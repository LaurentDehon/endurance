<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h2 class="text-3xl font-bold text-gray-800">Activities</h2>
        
        <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
            <!-- Search -->
            <div class="relative flex-1">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..." class="w-full sm:w-64 px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-2.5 text-gray-400 hover:text-gray-600 transition-colors">✕</button>
                @endif
            </div>

            <!-- Items per page -->
            <select wire:model.live="perPage" class="px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 transition-all">
                <option value="10">10 / page</option>
                <option value="25">25 / page</option>
                <option value="50">50 / page</option>
            </select>

            <!-- Delete all -->
            <button wire:click.prevent="deleteAll" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">Delete all</button>
        </div>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-1/3 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('name')">
                            Activité
                            @include('components.sort-icon', ['field' => 'name'])
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('start_date')">
                            Date
                            @include('components.sort-icon', ['field' => 'start_date'])
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('distance')">
                            Distance
                            @include('components.sort-icon', ['field' => 'distance'])
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('moving_time')">
                            Time
                            @include('components.sort-icon', ['field' => 'moving_time'])
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer" 
                            wire:click="sortBy('total_elevation_gain')">
                            Elevation
                            @include('components.sort-icon', ['field' => 'total_elevation_gain'])
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="w-1/3 px-6 py-4 whitespace-nowrap">{{ Str::limit($activity->name, 25) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $activity->start_date->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ number_format($activity->distance / 1000, 2) }}km
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ formatTime($activity->moving_time) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $activity->total_elevation_gain }}m
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="{{ route('activities', $activity) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button wire:click.prevent="delete({{ $activity->id }})" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                               No activity found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>