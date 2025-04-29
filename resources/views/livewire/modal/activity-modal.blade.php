<div class="bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-lg shadow-xl z-50 max-w-2xl w-full relative overflow-hidden">
    <!-- Top decorative bar with Strava brand color -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-600"></div>
    
    <!-- Activity header with Strava-inspired style -->
    <div class="p-6 pb-2">
        <div class="flex justify-between items-start mb-4">
            <h2 class="text-xl md:text-2xl font-bold text-white mb-1 flex items-center">
                <i class="fab fa-strava text-amber-500 mr-3"></i>
                {{ $activity->name }}
            </h2>
            <button type="button" wire:click.prevent="close" 
                class="text-white bg-cyan-600 hover:bg-cyan-500 h-8 w-8 rounded-full flex items-center justify-center hover:scale-105 transition-all"
                aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-cyan-200 text-sm">
            {{ $activity->start_date->format('l, F j, Y') }} <span class="text-slate-300">at {{ $activity->start_date->format('H:i') }}</span>
        </p>
    </div>
    
    <div class="p-6 pt-2">
        <!-- Activity details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-4">
            <!-- Basic Info Column -->
            <div class="space-y-3 md:space-y-4">
                <div class="flex items-center gap-3 p-3 bg-white bg-opacity-10 border-white shadow-sm border border-opacity-10 rounded-xl">
                    <div class="p-2.5 bg-blue-500/10 rounded-lg flex items-center justify-center w-10 h-10">
                        <i class="fas fa-route text-blue-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Distance</p>
                        <p class="font-semibold text-white">
                            {{ number_format($activity->distance / 1000, 2) }} km
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-white bg-opacity-10 border-white shadow-sm border border-opacity-10 rounded-xl">
                    <div class="p-2.5 bg-green-500/10 rounded-lg flex items-center justify-center w-10 h-10">
                        <i class="fas fa-stopwatch text-green-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Moving Time</p>
                        <p class="font-semibold text-white">
                            {{ formatTime($activity->moving_time) }}
                        </p>
                    </div>
                </div>            

                <div class="flex items-center gap-3 p-3 bg-white bg-opacity-10 border-white shadow-sm border border-opacity-10 rounded-xl">
                    <div class="p-2.5 bg-purple-500/10 rounded-lg flex items-center justify-center w-10 h-10">
                        <i class="fas fa-mountain text-purple-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Elevation Gain</p>
                        <p class="font-semibold text-white">
                            {{ $activity->total_elevation_gain }} m
                        </p>
                    </div>
                </div>
            </div>

            <!-- Advanced Stats Column -->
            <div class="space-y-3 md:space-y-4">
                <!-- Average Pace -->
                <div class="flex items-center gap-3 p-3 bg-white bg-opacity-10 border-white shadow-sm border border-opacity-10 rounded-xl">
                    <div class="p-2.5 bg-yellow-500/10 rounded-lg flex items-center justify-center w-10 h-10">
                        <i class="fas fa-tachometer-alt text-yellow-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Average Pace</p>
                        <p class="font-semibold text-white">
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

                <div class="flex items-center gap-3 p-3 bg-white bg-opacity-10 border-white shadow-sm border border-opacity-10 rounded-xl">
                    <div class="p-2.5 bg-red-500/10 rounded-lg flex items-center justify-center w-10 h-10">
                        <i class="fas fa-heartbeat text-red-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Heart Rate</p>
                        <p class="font-semibold text-white">
                            {{ $activity->average_heartrate ?? 'N/A' }} 
                            @if($activity->max_heartrate)
                                <span class="text-slate-300">(Max {{ $activity->max_heartrate }})</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 bg-white bg-opacity-10 border-white shadow-sm border border-opacity-10 rounded-xl">
                    <div class="p-2.5 bg-indigo-500/10 rounded-lg flex items-center justify-center w-10 h-10">
                        <i class="fas fa-sync-alt text-indigo-500 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-sm text-slate-300">Synced</p>
                        <p class="font-semibold text-white">
                            {{ \Carbon\Carbon::parse($activity->sync_date)->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal footer -->
        <div class="flex flex-col sm:flex-row gap-3 mt-6 border-t border-white border-opacity-20 pt-5">
            <a href="https://www.strava.com/activities/{{ $activity->strava_id }}" target="_blank" 
                class="flex items-center justify-center gap-2 py-2.5 px-4 text-sm font-medium text-white bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl hover:from-amber-600 hover:to-orange-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-500/50 flex-1 hover:shadow-md">
                <i class="fab fa-strava mr-1.5"></i>
                View
            </a>
            <button type="button" wire:click.prevent="delete" 
                class="bg-red-600 hover:bg-red-500 text-white flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-300/50 hover:shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-trash-alt mr-1.5"></i>
                Delete
            </button>
            <button type="button" wire:click.prevent="close" 
                class="text-white bg-cyan-600 hover:bg-cyan-500 flex-1 px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300/50">
                <i class="fas fa-times mr-1.5"></i>
                Close
            </button>
        </div>
    </div>
</div>