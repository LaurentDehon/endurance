<div class="{{ themeClass('modal-bg') }} border rounded-lg px-6 py-4">
    <h2 class="text-xl font-bold mb-4 {{ themeClass('text-1') }}">Select Week Type</h2>
    <div class="flex flex-col space-y-2 mb-4">
        <button 
            wire:click="selectWeekType()" class="flex items-center gap-2 p-3 rounded-lg {{ themeClass('button') }} {{ $currentTypeId === null ? 'border-2 ' . themeClass('border-accent') : '' }}">
            <span class="w-4 h-4 rounded-full bg-gray-300"></span>
            <span class="font-medium">None</span>
        </button>
        
        @foreach ($weekTypes as $type)
            <button 
                wire:click="selectWeekType({{ $type->id }})" class="flex items-center gap-2 p-3 rounded-lg {{ themeClass('button') }} {{ $currentTypeId == $type->id ? 'border-2 ' . themeClass('border-accent') : '' }}">
                <span class="w-4 h-4 rounded-full {{ $type->color }}"></span>
                <span class="font-medium">{{ $type->name }}</span>
            </button>
        @endforeach
    </div>
    
    <div class="flex justify-end mt-6">
        <button
        wire:click="$dispatch('closeModal')" class="px-4 py-2 rounded-md text-sm {{ themeClass('button') }}">
            Cancel
        </button>
    </div>
</div>
