<div>
    <div
        x-data="{ show: @entangle('show') }"
        x-show="show"
        x-on:keydown.escape.window="show && @this.closeModal()"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <div 
            class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0"
            x-on:click="@this.closeable && @this.closeModal()"
        >
            <!-- Background overlay -->
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0" 
                x-transition:enter-end="opacity-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100" 
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 {{ themeClass('background') }} backdrop-blur-sm transition-opacity"
                aria-hidden="true"
            ></div>

            <!-- Modal panel -->
            <div 
                x-show="show" 
                x-transition:enter="ease-out duration-300" 
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave="ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-transparent rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $this->getMaxWidthClass() }}"
                x-on:click.stop
                role="dialog" 
                aria-modal="true" 
                aria-labelledby="modal-headline"
            >
                @if($show && $component)
                    @livewire($component, $componentAttributes, key($component))
                @endif
            </div>
        </div>
    </div>
</div>
