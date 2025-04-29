<div>
    @if($showModal)
    <div 
        class="fixed inset-0 z-[9999]"
        x-data
        x-on:click="(e) => { if(e.target === $el) $wire.cancel() }"
    >
        <!-- La partie semi-transparente ne couvre que la modale -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" aria-hidden="true"></div>
        
        <!-- La modale en elle-même -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                x-data
                x-init="
                    setTimeout(() => $el.classList.add('opacity-100', 'scale-100'), 10);
                    document.addEventListener('keydown', e => {
                        if (e.key === 'Escape') $wire.cancel();
                    });
                "
                class="bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-lg shadow-xl max-w-md w-full overflow-hidden opacity-0 scale-95 transform transition-all duration-300"
                @click.outside="$wire.cancel()"
            >
                <!-- Barre décorative en haut -->
                <div class="h-1 w-full bg-gradient-to-r from-{{ $iconColor }}-500 to-{{ $iconColor }}-700"></div>
                
                <!-- En-tête -->
                <div class="px-6 py-5">
                    <div class="flex items-center">
                        <div class="mr-4 p-2 rounded-full bg-{{ $iconColor }}-100 text-{{ $iconColor }}-600">
                            <i class="fas fa-{{ $icon }} text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white">{{ $title }}</h3>
                    </div>
                </div>
                
                <!-- Corps -->
                <div class="px-6 pb-6 pt-2">
                    <p class="text-cyan-200 mb-6">
                        {!! $message !!}
                    </p>
                    
                    <!-- Boutons -->
                    <div class="flex justify-end gap-3">
                        <button wire:click="cancel" type="button" class="text-white bg-cyan-600 hover:bg-cyan-500 px-4 py-2 rounded-lg transition-colors">
                            {{ $cancelButtonText }}
                        </button>
                        <button wire:click="confirm" type="button" class="bg-amber-600 text-white hover:bg-amber-500 px-4 py-2 rounded-lg transition-colors">
                            {{ $confirmButtonText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
