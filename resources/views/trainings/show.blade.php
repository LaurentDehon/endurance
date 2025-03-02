<div class="bg-white rounded-xl shadow-2xl overflow-hidden">
    <!-- Update Training Form -->
    <form action="{{ route('trainings.update', $training) }}" method="POST" id="updateForm">
        @csrf
        @method('PUT')
        
        <!-- Enhanced Header -->
        <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-8 py-6 rounded-t-xl">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-running mr-3"></i>
                    Training Details
                </h1>            
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-8 space-y-1">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Date -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                        Date
                    </label>
                    <input type="date" name="date" 
                        value="{{ $training->date->format('Y-m-d') }}"
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
                </div>

                <!-- Training Type -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-tag mr-2 text-blue-600"></i>
                        Training Type
                    </label>
                    <select name="training_type_id" id="training_type_id" 
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 appearance-none bg-[url('data:image/svg+xml;base64,PHN2Zy...')] bg-no-repeat bg-right-4">
                            @foreach($trainingTypes as $type)
                                <option value="{{ $type->id }}" 
                                    {{ $training->training_type_id == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                </div>

                <!-- Distance -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-route mr-2 text-blue-600"></i>
                        Distance (km)
                    </label>
                    <input type="number" step="0.1" name="distance" placeholder="0.0" value="{{ number_format($training->distance, 1) }}"
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500">
                </div>

                <!-- Duration -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-stopwatch mr-2 text-blue-600"></i>
                        Time
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative">
                            <input type="number" name="hours" value="{{ floor($training->duration / 3600) }}" min="0" placeholder=0                            
                                class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl pr-12">
                            <span class="absolute right-6 top-4 text-gray-400">h</span>
                        </div>
                        <div class="relative">
                            <input type="number" name="minutes" value="{{ ($training->duration / 60) % 60 }}" min="0" max="59" placeholder=0
                                class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl pr-12">
                            <span class="absolute right-6 top-4 text-gray-400">m</span>
                        </div>
                    </div>
                </div>

                <!-- Elevation -->
                <div class="col-span-full space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-mountain mr-2 text-blue-600"></i>
                        Elevation Gain (m)
                    </label>
                    <input type="number" name="elevation" value="{{ $training->elevation ?? 0 }}" placeholder=0
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500">
                </div>

                <!-- Notes Section -->
                <div class="col-span-full space-y-4 pb-5">
                    <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                        <i class="fas fa-sticky-note mr-2 text-blue-600"></i>
                        Notes
                    </label>
                    <textarea name="notes" rows="4"
                        class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500">{{ $training->notes }}
                    </textarea>
                </div>        
            </div>
        </div>
    </form>

    <!-- Delete Training Form -->
    <form action="{{ route('trainings.destroy', $training) }}" method="POST" id="deleteForm">
        @csrf
        @method('DELETE')
    </form>

    <!-- Combined Action Buttons -->
    <div class="flex justify-between border-t px-8 py-6">
        <div class="flex gap-3">
            <!-- Delete Button (bound to deleteForm) -->
            <button type="submit" form="deleteForm" 
                class="px-6 py-3.5 h-[48px] bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-semibold shadow-lg shadow-red-100 hover:shadow-md flex items-center justify-center">
                Delete
            </button>

            <!-- Save Button (bound to updateForm) -->
            <button type="submit" form="updateForm" 
                class="px-6 py-3.5 h-[48px] bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold shadow-lg shadow-blue-100 hover:shadow-md flex items-center justify-center">
                Save
            </button>
        </div>

        <button type="button" onclick="closeModal()" 
            class="px-6 py-3.5 h-[48px] rounded-xl border-2 border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors flex items-center justify-center">
            Close
        </button>
    </div>
</div>