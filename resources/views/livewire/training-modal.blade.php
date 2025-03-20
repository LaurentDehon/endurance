<div class="p-4 md:p-8 bg-white rounded-xl shadow-xl z-50 relative">
    <div class="text-center mb-6 md:mb-8 pt-4">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2" id="modal-title">
            {{ $trainingId ? 'Edit Training' : 'Create New Training' }}
        </h2>
        <p class="text-xs md:text-sm text-gray-500">
            {{ $trainingId ? 'Update your training session details' : 'Enter the details of your session' }}
        </p>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 md:space-y-6">
        <div class="grid grid-cols-1 gap-3 md:gap-4">
            <!-- Date -->
            <div class="space-y-1">
                <label for="date" class="text-sm font-medium text-gray-700">Date</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400 text-sm"></i>
                    </div>
                    <input type="date" wire:model="date" id="date" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-colors">
                    @error('date')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Training Type -->
            <div class="space-y-1">
                <label for="training_type_id" class="text-sm font-medium text-gray-700">Training Type</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-tag text-gray-400 text-sm"></i>
                    </div>
                    <select wire:model="trainingTypeId" id="training_type_id" 
                        class="pl-10 pr-8 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 appearance-none bg-white">
                        @foreach($trainingTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </div>
                    @error('trainingTypeId')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Distance -->
            <div class="space-y-1">
                <label for="distance" class="text-sm font-medium text-gray-700">Distance (km)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-route text-gray-400 text-sm"></i>
                    </div>
                    <input type="number" step="0.5" id="distance" wire:model.lazy="distance" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="0.0">
                    @error('distance')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Elevation -->
            <div class="space-y-1">
                <label for="elevation" class="text-sm font-medium text-gray-700">Elevation (m)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-mountain text-gray-400 text-sm"></i>
                    </div>
                    <input type="number" wire:model="elevation" id="elevation" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        placeholder="0">
                    @error('elevation')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Duration -->
            <div class="col-span-full space-y-1">
                <label class="text-sm font-medium text-gray-700">Duration</label>
                <div class="flex flex-col space-y-2 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">
                    <!-- Hours -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-clock text-gray-400 text-sm"></i>
                        </div>
                        <input type="number" wire:model="hours" id="hours" min="0" 
                            class="pl-10 pr-12 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            placeholder="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">hrs</span>
                    </div>

                    <!-- Minutes -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-stopwatch text-gray-400 text-sm"></i>
                        </div>
                        <input type="number" wire:model="minutes" id="minutes" min="0" max="59" 
                            class="pl-10 pr-12 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                            placeholder="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">min</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    @error('hours')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    @error('minutes')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="col-span-full space-y-1">
                <label for="notes" class="text-sm font-medium text-gray-700">Notes</label>
                <div class="relative">
                    <div class="absolute top-2.5 left-3">
                        <i class="fas fa-comment text-gray-400 text-sm"></i>
                    </div>
                    <textarea wire:model="notes" id="notes" rows="3" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200" 
                        placeholder="Write additional notes..."></textarea>
                    @error('notes')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        @if(!$trainingId)
            <div class="col-span-full space-y-4">
                <div class="flex items-center space-x-2">
                    <input type="checkbox" wire:model="isRecurring" id="isRecurring" 
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <label for="isRecurring" class="text-sm font-medium text-gray-700">Recurring Training</label>
                </div>

                <div x-data x-show="$wire.isRecurring" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <!-- Interval -->
                        <div class="space-y-1">
                            <label for="recurrenceInterval" class="text-sm font-medium text-gray-700">Repeat every (days)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-repeat text-gray-400 text-sm"></i>
                                </div>
                                <input type="number" wire:model="recurrenceInterval" id="recurrenceInterval" min="1" 
                                    class="pl-10 pr-4 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                                    placeholder="7">
                                @error('recurrenceInterval')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="space-y-1">
                            <label for="recurrenceEndDate" class="text-sm font-medium text-gray-700">End Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-times text-gray-400 text-sm"></i>
                                </div>
                                <input type="date" wire:model="recurrenceEndDate" id="recurrenceEndDate" class="pl-10 pr-4 py-2.5 w-full rounded-md border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                @error('recurrenceEndDate')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal footer -->
        <div class="flex flex-col md:flex-row gap-3 {{ $trainingId ? 'md:justify-between' : 'md:justify-end' }}">
            @if($trainingId)
                <button type="button" wire:click.prevent="delete" class="w-full md:w-1/3 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors duration-200">
                    Delete session
                </button>
            @endif
            
            <button type="submit" class="w-full md:w-1/3 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 transition-colors duration-200">
                {{ $trainingId ? 'Save Changes' : 'Create Session' }}
            </button>
        </div>
    </form>
</div>