<!-- Modal Container with Shadow and Rounded Corners -->
<div class="bg-white rounded-xl shadow-2xl overflow-hidden">
    <!-- Header with Enhanced Gradient -->
    <div class="bg-gradient-to-r from-blue-700 to-blue-600 px-8 py-6 rounded-t-xl">
        <h1 class="text-2xl font-bold text-white flex items-center">
            <i class="fas fa-running mr-3"></i>
            Create New Training Session
        </h1>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('trainings.store') }}" class="p-8 space-y-1">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Date Input -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                    Session Date
                </label>
                <input type="date" name="date" id="date" 
                    value="{{ $date->format('Y-m-d') }}"
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 transition-all">
            </div>

            <!-- Training Type -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-tag mr-2 text-blue-600"></i>
                    Training Type
                </label>
                <select name="type" id="type" 
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500 appearance-none bg-[url('data:image/svg+xml;base64,PHN2Zy...')] bg-no-repeat bg-right-4">
                    @foreach($trainingTypes as $type)
                        <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Distance Target -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-route mr-2 text-blue-600"></i>
                    Distance (km)
                </label>
                <input type="number" step="0.1" name="distance" id="distance" 
                    value="{{ old('distance', 10) }}"
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500"
                    placeholder="0.0">
            </div>

            <!-- Duration Target -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-stopwatch mr-2 text-blue-600"></i>
                    Time
                </label>
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="number" name="hours" 
                            value="{{ old('hours', 1) }}"
                            min="0" 
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl pr-12"
                            placeholder=0>
                        <span class="absolute right-4 top-4 text-gray-400">hrs</span>
                    </div>
                    <div class="relative">
                        <input type="number" name="minutes" 
                            value="{{ old('minutes', 0) }}"
                            min="0" max="59"
                            class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl pr-12"
                            placeholder=0>
                        <span class="absolute right-4 top-4 text-gray-400">mins</span>
                    </div>
                </div>
            </div>

            <!-- Elevation Target -->
            <div class="col-span-full space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-mountain mr-2 text-blue-600"></i>
                    Elevation Gain (m)
                </label>
                <input type="number" name="elevation" id="elevation" 
                    value="{{ old('elevation') }}"
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500"
                    placeholder=0>
            </div>

            <!-- Notes -->
            <div class="col-span-full space-y-4 pb-5">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-sticky-note mr-2 text-blue-600"></i>
                    Notes
                </label>
                <textarea name="notes" id="notes" 
                    class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500"
                    placeholder="Add any additional notes here" rows="4">{{ old('notes') }}</textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end space-x-4 border-t pt-8">
            <button type="button" onclick="closeModal()" 
                class="px-6 py-3.5 h-[48px] rounded-xl border-2 border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors flex items-center justify-center">
                Cancel
            </button>
            <button type="submit" class="px-6 py-3.5 h-[48px] bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold shadow-lg shadow-blue-100 hover:shadow-md flex items-center justify-center">
                Save Training
            </button>
        </div>
    </form>
</div>
