@props(['status'])

@if ($status)
    <div class="bg-green-500 bg-opacity-20 border border-green-500 border-opacity-30 text-green-100 rounded-lg p-4 mb-6">
        <div class="flex items-center">
            <svg class="h-5 w-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ $status }}</span>
        </div>
    </div>
@endif