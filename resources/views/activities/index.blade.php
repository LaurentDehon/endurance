@extends('layouts.app')

@section('content')

<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Activities Overview</h2>
        <form action="#" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                Delete All Activities
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold uppercase tracking-wider text-sm">Activity</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Date</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Distance</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Moving Time</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Elapsed Time</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Avg Speed</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Max Speed</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Avg HR</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Max HR</th>
                        <th class="py-4 px-6 font-semibold uppercase tracking-wider text-sm">Elevation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50 transition-colors duration-200 even:bg-gray-50">
                            <td class="text-gray-800 py-4 px-6 font-medium">{{ Str::limit($activity->name, 20) }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center">{{ \Carbon\Carbon::parse($activity->start_date)->format('M j, Y') }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ number_format($activity->distance / 1000, 2) }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ gmdate('H:i:s', $activity->moving_time) }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ gmdate('H:i:s', $activity->elapsed_time) }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ number_format($activity->average_speed * 3.6, 1) }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ number_format($activity->max_speed * 3.6, 1) }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ $activity->average_heartrate ?? 'N/A' }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ $activity->max_heartrate ?? 'N/A' }}</td>
                            <td class="text-gray-600 py-4 px-6 text-center font-mono">{{ number_format($activity->total_elevation_gain, 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-gray-500 py-6 px-6 text-center">No activities found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- @if($activities->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @endif --}}
    </div>
</div>

@endsection