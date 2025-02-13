<!-- Modal Container -->
<div class="bg-white rounded-xl shadow-2xl overflow-hidden">
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
        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Date -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                    Date
                </label>
                <div class="p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                    <time class="text-gray-700 font-medium">
                        {{ $training->date->isoFormat('dddd D MMMM YYYY') }}
                    </time>
                </div>
            </div>

            <!-- Training Type -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-tag mr-2 text-blue-600"></i>
                    Training Type
                </label>
                <div class="p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                    <span class="text-blue-600 font-semibold">
                        {{ $training->trainingType->name }}
                    </span>
                </div>
            </div>

            <!-- Distance -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-route mr-2 text-blue-600"></i>
                    Distance (km)
                </label>
                <div class="p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                    <span class="text-blue-600 font-semibold">
                        {{ number_format($training->distance, 1) }} km
                    </span>
                </div>
            </div>

            <!-- Duration -->
            <div class="space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-stopwatch mr-2 text-blue-600"></i>
                    Duration
                </label>
                <div class="p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                    <span class="text-blue-600 font-semibold">
                        {{ formatTime($training->duration) }}
                    </span>
                </div>
            </div>

            <!-- Elevation -->
            <div class="col-span-full space-y-4">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-mountain mr-2 text-blue-600"></i>
                    Elevation Gain (m)
                </label>
                <div class="p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                    <span class="text-blue-600 font-semibold">
                        {{ $training->elevation == null ? 0 : $training->elevation . ' meters'}}
                    </span>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="col-span-full space-y-4 pb-5">
                <label class="block text-sm font-semibold text-gray-700 uppercase tracking-wider">
                    <i class="fas fa-sticky-note mr-2 text-blue-600"></i>
                    Notes
                </label>
                <textarea class="w-full px-4 py-3.5 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-200 focus:border-blue-500" rows="4" readonly>{{ $training->notes }}</textarea>
            </div>        
        </div>

        
        <!-- Buttons -->
        <div class="flex justify-between border-t pt-8">
            <!-- Boutons Edit et Delete à gauche -->
            <div class="flex gap-3">
                <form action="{{ route('trainings.destroy', $training) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="px-6 py-3.5 h-[48px] bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors font-semibold shadow-lg shadow-red-100 hover:shadow-md flex items-center justify-center">
                        Delete
                    </button>
                </form>
                
                <a href="{{ route('trainings.edit', $training) }}" 
                    class="px-6 py-3.5 h-[48px] bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold shadow-lg shadow-blue-100 hover:shadow-md flex items-center justify-center">
                    Edit
                </a>
            </div>           

            <!-- Bouton Close tout à droite -->
            <button onclick="closeModal()" 
                class="px-6 py-3.5 h-[48px] rounded-xl border-2 border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors flex items-center justify-center">
                Close
            </button>
        </div>
    </div>
</div>