<div class="p-8 bg-white rounded-xl shadow-xl z-50">
    <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900" id="modal-title">
            {{ $trainingId ? 'Edit Training' : 'Create New Training' }}
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            {{ $trainingId ? 'Update your training session details' : 'Enter the details of your session' }}
        </p>
    </div>

    <form wire:submit.prevent="save" class="mt-3 space-y-5">
        <!-- Date -->        
        <div>
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            {{-- <x-ts-date format="YYYY, MMMM, DD" wire:model="date"/> --}}
            <div class="relative">
                <input type="date" wire:model="date" id="date" 
                    class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                @error('date')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Training Type -->
        <div>
            <label for="training_type_id" class="block text-sm font-medium text-gray-700 mb-1">Training Type</label>
            <div class="relative">
                <select wire:model="trainingTypeId" id="training_type_id" 
                    class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    @foreach($trainingTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                @error('trainingTypeId')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>
            {{-- <x-ts-select.native :options="$trainingTypes" select="label:name|value:id" wire:model="trainingTypeId" /> --}}
        </div>

        <!-- Distance -->
        <div>
            <label for="distance" class="block text-sm font-medium text-gray-700 mb-1">Distance (km)</label>
            <div class="relative">
                <input type="number" step="0.5" id="distance" wire:model.lazy="distance" wire:change.debounce="formatDistance"
                    class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                @error('distance')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Duration -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="hours" class="block text-sm font-medium text-gray-700 mb-1">Hours</label>
                <input type="number" wire:model="hours" id="hours" min="0" 
                    class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                @error('hours')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label for="minutes" class="block text-sm font-medium text-gray-700 mb-1">Minutes</label>
                <input type="number" wire:model="minutes" id="minutes" min="0" max="59" 
                    class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                @error('minutes')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Elevation -->
        <div>
            <label for="elevation" class="block text-sm font-medium text-gray-700 mb-1">Elevation (m)</label>
            <div class="relative">
                <input type="number" wire:model="elevation" id="elevation" 
                    class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                @error('elevation')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Notes -->
        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
            <div class="relative">
                <textarea wire:model="notes" id="notes" rows="3" 
                    class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"></textarea>
                @error('notes')
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Modal footer -->
        <div class="flex items-center justify-between space-x-4">
            @if($trainingId)
                <button type="button" 
                    wire:click.prevent="delete"
                    class="px-4 py-3 font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200">
                    Delete
                </button>
            @endif
            
            <button type="submit" 
                class="ml-auto w-1/3 px-4 py-3 font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200">
                {{ $trainingId ? 'Save Changes' : 'Save' }}
            </button>
        </div>
    </form>
</div>
