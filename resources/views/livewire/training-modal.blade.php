<div class="{{ themeClass('modal-bg') }} border rounded-lg shadow-xl z-50 max-w-3xl w-full relative overflow-hidden">
    <!-- Top decorative bar -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
    
    <!-- Header with improved styles -->
    <div class="p-6 pb-2">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl md:text-2xl font-bold {{ themeClass('text-1') }} mb-1 flex items-center" id="modal-title">
                <i class="fas fa-{{ $trainingId ? 'edit' : 'plus-circle' }} {{ themeClass('text-accent') }} mr-3"></i>
                {{ $trainingId ? 'Edit Workout' : 'Create New Workout' }}
            </h2>
            <button type="button" wire:click.prevent="close" 
                class="{{ themeClass('button') }} h-8 w-8 rounded-full flex items-center justify-center hover:scale-105 transition-all"
                aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="{{ themeClass('text-2') }} text-sm mb-4">
            {{ $trainingId ? 'Update your training session details' : 'Enter the details of your session' }}
        </p>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 md:space-y-6 p-6 pt-0">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
            <!-- Date -->
            <div class="space-y-1.5">
                <label for="date" class="text-sm font-medium {{ themeClass('text-1') }}">Date</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt {{ themeClass('text-3') }} text-sm"></i>
                    </div>
                    <input type="date" wire:model="date" id="date" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all">
                    @error('date')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Training Type -->
            <div class="space-y-1.5">
                <label for="training_type_id" class="text-sm font-medium {{ themeClass('text-1') }}">Training Type</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-tag {{ themeClass('text-3') }} text-sm"></i>
                    </div>
                    <select wire:model="trainingTypeId" id="training_type_id" 
                        class="pl-10 pr-8 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 appearance-none transition-all">
                        @foreach($trainingTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down {{ themeClass('text-3') }} text-sm"></i>
                    </div>
                    @error('trainingTypeId')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Distance -->
            <div class="space-y-1.5">
                <label for="distance" class="text-sm font-medium {{ themeClass('text-1') }}">Distance (km)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-route {{ themeClass('text-3') }} text-sm"></i>
                    </div>
                    <input type="number" step="0.5" id="distance" wire:model.lazy="distance" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all"
                        placeholder="0.0">
                    @error('distance')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Elevation -->
            <div class="space-y-1.5">
                <label for="elevation" class="text-sm font-medium {{ themeClass('text-1') }}">Elevation (m)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-mountain {{ themeClass('text-3') }} text-sm"></i>
                    </div>
                    <input type="number" wire:model="elevation" id="elevation" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all"
                        placeholder="0">
                    @error('elevation')
                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Duration -->
            <div class="col-span-full space-y-1.5">
                <label class="text-sm font-medium {{ themeClass('text-1') }}">Duration</label>
                <div class="flex flex-col space-y-2 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">
                    <!-- Hours -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-clock {{ themeClass('text-3') }} text-sm"></i>
                        </div>
                        <input type="number" wire:model="hours" id="hours" min="0" 
                            class="pl-10 pr-12 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all"
                            placeholder="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 {{ themeClass('text-3') }} text-sm">hrs</span>
                    </div>

                    <!-- Minutes -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-stopwatch {{ themeClass('text-3') }} text-sm"></i>
                        </div>
                        <input type="number" wire:model="minutes" id="minutes" min="0" max="59" 
                            class="pl-10 pr-12 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all"
                            placeholder="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 {{ themeClass('text-3') }} text-sm">min</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    @error('hours')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    @error('minutes')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="col-span-full space-y-1.5">
                <label for="notes" class="text-sm font-medium {{ themeClass('text-1') }}">Notes</label>
                <div class="relative">
                    <div class="absolute top-2.5 left-3">
                        <i class="fas fa-comment {{ themeClass('text-3') }} text-sm"></i>
                    </div>
                    <textarea wire:model="notes" id="notes" rows="3" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all" 
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
                        class="rounded {{ themeClass('checkbox') }} shadow-sm focus:ring focus:ring-blue-400/20 focus:ring-opacity-50">
                    <label for="isRecurring" class="text-sm font-medium {{ themeClass('text-1') }}">Recurring Training</label>
                </div>

                <div x-data x-show="$wire.isRecurring" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="border-t {{ themeClass('divider') }} pt-4 mt-4">
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <!-- Interval -->
                        <div class="space-y-1.5">
                            <label for="recurrenceInterval" class="text-sm font-medium {{ themeClass('text-1') }}">Repeat every (days)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-repeat {{ themeClass('text-3') }} text-sm"></i>
                                </div>
                                <input type="number" wire:model="recurrenceInterval" id="recurrenceInterval" min="1" 
                                    class="pl-10 pr-4 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all"
                                    placeholder="7">
                                @error('recurrenceInterval')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message ?? '' }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="space-y-1.5">
                            <label for="recurrenceEndDate" class="text-sm font-medium {{ themeClass('text-1') }}">End Date</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-times {{ themeClass('text-3') }} text-sm"></i>
                                </div>
                                <input type="date" wire:model="recurrenceEndDate" id="recurrenceEndDate" 
                                    class="pl-10 pr-4 py-2.5 w-full rounded-lg {{ themeClass('input') }} focus:ring-2 focus:ring-blue-400/20 transition-all">
                                @error('recurrenceEndDate')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message ?? '' }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal footer with improved styling -->
        <div class="mt-6 border-t {{ themeClass('divider') }} pt-5 flex flex-col sm:flex-row gap-3">
            <!-- Bouton Close -->
            <button type="button" wire:click.prevent="close" 
                class="{{ themeClass('button') }} flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300/50">
                <i class="fas fa-times mr-1.5"></i>
                Close
            </button>

            @if($trainingId)
                <!-- Bouton Delete -->
                <button type="button" wire:click.prevent="delete" 
                    class="{{ themeClass('button-danger') }} flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-300/50 hover:shadow-md">
                    <i class="fas fa-trash-alt mr-1.5"></i>
                    Delete session
                </button>
            @endif
            
            <!-- Bouton Submit -->
            <button type="submit" 
                class="{{ themeClass('button-accent') }} flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400/50 hover:shadow-md">
                <i class="fas fa-{{ $trainingId ? 'save' : 'plus' }} mr-1.5"></i>
                {{ $trainingId ? 'Save Changes' : 'Create Session' }}
            </button>
        </div>
    </form>
</div>