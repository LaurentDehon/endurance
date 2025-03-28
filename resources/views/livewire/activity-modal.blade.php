<div class="p-8 bg-white rounded-xl shadow-xl z-50 max-w-2xl w-full relative">
    <div class="text-center mb-8">
        <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-2">
            {{ $activity->name }}
        </h2>
        <p class="text-xs md:text-sm text-gray-500">
            {{ $activity->start_date->format('M j, Y H:i') }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
        <!-- Basic Info -->
        <div class="space-y-3 md:space-y-4">
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i class="fas fa-route text-blue-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Distance</p>
                    <p class="font-semibold text-gray-800">
                        {{ number_format($activity->distance / 1000, 2) }} km
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-stopwatch text-green-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Moving Time</p>
                    <p class="font-semibold text-gray-800">
                        {{ formatTime($activity->moving_time) }}
                    </p>
                </div>
            </div>            

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i class="fas fa-mountain text-purple-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Elevation Gain</p>
                    <p class="font-semibold text-gray-800">
                        {{ $activity->total_elevation_gain }} m
                    </p>
                </div>
            </div>
        </div>

        <!-- Advanced Stats -->
        <div class="space-y-3 md:space-y-4">
            <!-- Average Pace -->
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Average Pace</p>
                    <p class="font-semibold text-gray-800">
                        @if($activity->average_speed > 0)
                            @php
                                $secondsPerKm = 1000 / $activity->average_speed;
                                $minutes = floor($secondsPerKm / 60);
                                $seconds = floor($secondsPerKm % 60);
                            @endphp
                            {{ $minutes }}:{{ str_pad($seconds, 2, '0', STR_PAD_LEFT) }} /km
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="p-2 bg-red-100 rounded-lg">
                    <i class="fas fa-heartbeat text-red-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Heart Rate</p>
                    <p class="font-semibold text-gray-800">
                        {{ $activity->average_heartrate ?? 'N/A' }} 
                        @if($activity->max_heartrate)
                            <span class="text-gray-400">(Max {{ $activity->max_heartrate }})</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i class="fas fa-sync-alt text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Synced</p>
                    <p class="font-semibold text-gray-800">
                        {{ \Carbon\Carbon::parse($activity->sync_date)->diffForHumans() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal footer -->
    <div class="flex flex-col md:flex-row mt-6">
        <a href="https://www.strava.com/activities/{{ $activity->strava_id }}" target="_blank" 
            class="w-1/3 px-4 py-2 text-sm font-medium text-white bg-orange-500 rounded-md hover:bg-orange-600 transition-colors duration-200 focus:outline-none text-center">
            View on Strava
        </a>
        <button type="button" wire:click.prevent="close" 
            class="ml-auto w-1/3 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200 focus:outline-none">
            Close
        </button>
    </div>
</div>