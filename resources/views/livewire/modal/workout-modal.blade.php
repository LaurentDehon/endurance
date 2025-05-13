<div class="bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-lg shadow-xl z-50 max-w-3xl w-full relative overflow-hidden">
    <!-- Top decorative bar -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
    
    <!-- Hide number input spinners -->
    <style>
        /* Chrome, Safari, Edge, Opera */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    
    <!-- Script pour initialiser Alpine.js spécifiquement dans ce composant -->
    <script>
        document.addEventListener('livewire:load', function() {
            if (window.Alpine) {
                // Force Alpine à évaluer cette section après le chargement du composant
                window.Alpine.initTree(document.getElementById('workout-type-dropdown'));
            }
        });
    </script>
    
    <!-- Header with improved styles -->
    <div class="p-6 pb-2">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-white mb-1 flex items-center" id="modal-title">
                <i class="fas fa-{{ $workoutId ? 'edit' : 'plus-circle' }} text-amber-300 mr-3"></i>
                {{ $workoutId ? __('workouts.modal.Edit Workout') : __('workouts.modal.Create New Workout') }}
            </h2>
            <button type="button" wire:click.prevent="close" 
                class="text-white bg-cyan-600 hover:bg-cyan-500 h-8 w-8 rounded-full flex items-center justify-center hover:scale-105 transition-all"
                aria-label="{{ __('workouts.modal.Close') }}">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-cyan-200 text-sm mb-4">
            {{ $workoutId ? __('workouts.modal.Update your workout session details') : __('workouts.modal.Enter the details of your session') }}
        </p>
    </div>

    <form wire:submit.prevent="save" class="space-y-4 md:space-y-6 p-6 pt-0">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
            <!-- Date -->
            <div class="space-y-1.5">
                <label for="date" class="text-sm font-medium text-white">{{ __('workouts.modal.Date') }}</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-calendar-alt text-slate-300 text-sm"></i>
                    </div>
                    <input type="date" wire:model="date" id="date" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all">                    @error('date')
                        <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('date') }}</span>
                    @enderror
                </div>
            </div>

            <!-- Workout Type -->
            <div class="space-y-1.5">
                <label for="workout_type_id" class="text-sm font-medium text-white">{{ __('workouts.modal.Workout Type') }}</label>
                <div class="relative" x-data="{ typeMenuOpen: false }">
                    <!-- Bouton du dropdown -->
                    <button 
                        x-ref="toggleBtn"
                        @click="typeMenuOpen = !typeMenuOpen" 
                        type="button"
                        class="relative w-full flex items-center pl-10 pr-8 py-2.5 rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-tag text-slate-300 text-sm"></i>
                        </div>
                        <span class="block truncate text-left">
                            @foreach($workoutTypes as $type)
                                @if($workoutTypeId == $type->id)
                                    {{ $type->name }}
                                @endif
                            @endforeach
                        </span>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-slate-300 text-sm"></i>
                        </div>
                    </button>

                    <!-- Menu dropdown téléporté dans le body -->
                    <template x-teleport="body">
                        <div 
                            x-data
                            x-show="typeMenuOpen" 
                            x-init="$watch('typeMenuOpen', value => {
                                if (value) {
                                    $nextTick(() => {
                                        const rect = $refs.toggleBtn.getBoundingClientRect();
                                        $el.style.top = `${rect.bottom + window.scrollY + 5}px`;
                                        $el.style.left = `${rect.left}px`;
                                        $el.style.width = `${rect.width}px`;
                                    });
                                }
                            })"
                            @mousedown.window="!$refs.toggleBtn.contains($event.target) && !$el.contains($event.target) && (typeMenuOpen = false)"
                            x-transition:enter="transition ease-out duration-200" 
                            x-transition:enter-start="opacity-0 scale-95" 
                            x-transition:enter-end="opacity-100 scale-100" 
                            x-transition:leave="transition ease-in duration-175" 
                            x-transition:leave-start="opacity-100 scale-100" 
                            x-transition:leave-end="opacity-0 scale-95" 
                            class="p-2 bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg" 
                            x-cloak
                            style="position: absolute; z-index: 99999;">
                            <div class="py-1 overflow-y-auto">
                                @foreach($workoutTypes as $type)
                                    <button 
                                        wire:click="setWorkoutType({{ $type->id }})" 
                                        @click="typeMenuOpen = false"
                                        type="button"
                                        class="w-full text-left px-4 py-2.5 text-white hover:bg-white hover:bg-opacity-10 flex items-center gap-2 rounded-lg transition-colors">
                                        <span class="text-sm">{{ $type->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </template>

                    <!-- Input caché pour synchroniser le modèle -->
                    <input type="hidden" wire:model="workoutTypeId" id="workout_type_id">

                    @error('workoutTypeId')
                        <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('workoutTypeId') }}</span>
                    @enderror
                </div>
            </div>

            <!-- Distance -->
            <div class="space-y-1.5">
                <label for="distance" class="text-sm font-medium text-white">{{ __('workouts.modal.Distance') }} ({{ __('workouts.modal.km') }})</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-route text-slate-300 text-sm"></i>
                    </div>
                    <input type="number" step="0.5" id="distance" wire:model.lazy="distance" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all"
                        placeholder="0.0">
                    @error('distance')
                        <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('distance') }}</span>
                    @enderror
                </div>
            </div>

            <!-- Elevation -->
            <div class="space-y-1.5">
                <label for="elevation" class="text-sm font-medium text-white">{{ __('workouts.modal.Elevation') }} ({{ __('workouts.modal.m') }})</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-mountain text-slate-300 text-sm"></i>
                    </div>
                    <input type="number" wire:model="elevation" id="elevation" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all"
                        placeholder="0">
                    @error('elevation')
                        <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('elevation') }}</span>
                    @enderror
                </div>
            </div>

            <!-- Duration -->
            <div class="col-span-full space-y-1.5">
                <label class="text-sm font-medium text-white">{{ __('workouts.modal.Duration') }}</label>
                <div class="flex flex-col space-y-2 md:space-y-0 md:grid md:grid-cols-2 md:gap-4">
                    <!-- Hours -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-clock text-slate-300 text-sm"></i>
                        </div>
                        <input type="number" wire:model="hours" id="hours" min="0" 
                            class="pl-10 pr-12 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all"
                            placeholder="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 text-sm">{{ __('workouts.modal.hrs') }}</span>
                    </div>

                    <!-- Minutes -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-stopwatch text-slate-300 text-sm"></i>
                        </div>
                        <input type="number" wire:model="minutes" id="minutes" min="0" max="59" 
                            class="pl-10 pr-12 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all"
                            placeholder="0">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-300 text-sm">{{ __('workouts.modal.min') }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    @error('hours')<span class="text-red-500 text-xs">{{ $errors->first('hours') }}</span>@enderror
                    @error('minutes')<span class="text-red-500 text-xs">{{ $errors->first('minutes') }}</span>@enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="col-span-full space-y-1.5">
                <label for="notes" class="text-sm font-medium text-white">{{ __('workouts.modal.Notes') }}</label>
                <div class="relative">
                    <div class="absolute top-2.5 left-3">
                        <i class="fas fa-comment text-slate-300 text-sm"></i>
                    </div>
                    <textarea wire:model="notes" id="notes" rows="3" 
                        class="pl-10 pr-4 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all" 
                        placeholder="{{ __('workouts.modal.Write additional notes...') }}"></textarea>
                    @error('notes')
                        <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('notes') }}</span>
                    @enderror
                </div>
            </div>
        </div>

        @if(!$workoutId)
            <div class="col-span-full space-y-4">
                <div class="flex items-center space-x-2">
                    <div class="relative inline-flex items-center">
                        <input type="checkbox" wire:click="toggleRecurring" id="isRecurring" {{ $isRecurring ? 'checked' : '' }}
                            class="w-5 h-5 text-amber-600 bg-slate-700 rounded border-slate-600 focus:ring-2 focus:ring-amber-400/30 cursor-pointer transition-all">
                        <label for="isRecurring" class="ml-2 text-sm font-medium text-white cursor-pointer"></label>
                            <span class="flex items-center text-white">
                                <i class="fas fa-repeat text-amber-400 mr-1.5"></i>
                                {{ __('workouts.modal.Recurring Workout') }}
                            </span>
                        </label>
                    </div>
                </div>

                @if($isRecurring)
                <div class="border-t border-white border-opacity-20 pt-4 mt-4 transition-all duration-300 ease-in-out">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <!-- Interval -->
                        <div class="space-y-1.5">
                            <label for="recurrenceInterval" class="text-sm font-medium text-white">{{ __('workouts.modal.Repeat every (days)') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-repeat text-slate-300 text-sm"></i>
                                </div>
                                <input type="number" wire:model="recurrenceInterval" id="recurrenceInterval" min="1" 
                                    class="pl-10 pr-4 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all"
                                    placeholder="7">
                                @error('recurrenceInterval')
                                <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('recurrenceInterval') }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="space-y-1.5">
                            <label for="recurrenceEndDate" class="text-sm font-medium text-white">{{ __('workouts.modal.End Date') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-times text-slate-300 text-sm"></i>
                                </div>
                                <input type="date" wire:model="recurrenceEndDate" id="recurrenceEndDate" 
                                    class="pl-10 pr-4 py-2.5 w-full rounded-lg bg-slate-700 bg-opacity-60 text-white border-slate-600 border-opacity-50 focus:ring-2 focus:ring-blue-400/20 transition-all">
                                @error('recurrenceEndDate')
                                <span class="text-red-500 text-xs mt-1 block">{{ $errors->first('recurrenceEndDate') }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endif

        <!-- Modal footer with improved styling -->
        <div class="mt-6 border-t border-white border-opacity-20 pt-5 flex flex-col sm:flex-row gap-3">
            <!-- Bouton Close -->
            <button type="button" wire:click.prevent="close" 
                class="text-white bg-cyan-600 hover:bg-cyan-500 flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300/50">
                <i class="fas fa-times mr-1.5"></i>
                {{ __('workouts.modal.Close') }}
            </button>

            @if($workoutId)
                <!-- Bouton Delete -->
                <button type="button" wire:click.prevent="delete" 
                    class="bg-red-600 hover:bg-red-500 text-white flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-300/50 hover:shadow-md">
                    <i class="fas fa-trash-alt mr-1.5"></i>
                    {{ __('workouts.modal.Delete') }}
                </button>
            @endif
            
            <!-- Bouton Submit -->
            <button type="submit" 
                class="bg-amber-600 text-white hover:bg-amber-500 flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400/50 hover:shadow-md">
                <i class="fas fa-{{ $workoutId ? 'save' : 'plus' }} mr-1.5"></i>
                {{ $workoutId ? __('workouts.modal.Save') : __('workouts.modal.Create') }}
            </button>
        </div>
    </form>
</div>