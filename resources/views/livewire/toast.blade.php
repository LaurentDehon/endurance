<div 
    class="fixed z-50 top-4 right-4 max-w-96 w-auto space-y-2"
    wire:init="$refresh"
>
    @if(count($toasts) > 0)
        <!-- Débogage caché mais utile -->
        <div class="hidden">{{ count($toasts) }} toasts en attente</div>
    @endif
    
    @foreach($toasts as $toast)
        <div 
            wire:key="toast-{{ $toast['id'] }}"
            class="toast flex items-center p-4 rounded-lg shadow-sm transform transition-all duration-300"
            x-data="{ show: false }"
            x-init="
                setTimeout(() => { show = true }, 100);
                setTimeout(() => { 
                    show = false;
                    setTimeout(() => $wire.removeToast('{{ $toast['id'] }}'), 500);
                }, {{ $toast['duration'] - 500 }})
            "
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            :class="{
                'bg-green-100 text-green-800 shadow-green-200 shadow-opacity-20': '{{ $toast['type'] }}' === 'success',
                'bg-red-100 text-red-800 shadow-red-200 shadow-opacity-20': '{{ $toast['type'] }}' === 'error',
                'bg-yellow-100 text-yellow-800 shadow-yellow-200 shadow-opacity-20': '{{ $toast['type'] }}' === 'warning',
                'bg-blue-100 text-blue-800 shadow-blue-200 shadow-opacity-20': '{{ $toast['type'] }}' === 'info'
            }"
        >
            <div class="shrink-0 mr-3">
                @if($toast['type'] === 'success')
                    <i class="fas fa-check-circle text-xl"></i>
                @elseif($toast['type'] === 'error')
                    <i class="fas fa-times-circle text-xl"></i>
                @elseif($toast['type'] === 'warning')
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                @elseif($toast['type'] === 'info')
                    <i class="fas fa-info-circle text-xl"></i>
                @endif
            </div>
            <div class="flex-grow">
                {{ $toast['message'] }}
            </div>
            <button 
                type="button" 
                class="ml-3 shrink-0 text-gray-500 hover:text-gray-700"
                @click="show = false; $wire.removeToast('{{ $toast['id'] }}')"
            >
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    @endforeach
</div>
