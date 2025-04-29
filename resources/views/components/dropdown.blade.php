@props([
    'trigger' => 'button',
    'triggerClass' => '',
    'triggerIcon' => null,
    'triggerText' => null,
    'align' => 'left',
    'width' => 'w-60',
    'teleport' => true,
    'zIndex' => '99999',
    'autoClose' => true
])

<div x-data="{ 
    open: false,
    setPosition() {
        $nextTick(() => {
            const button = $root.querySelector('button') || $root.firstElementChild;
            const rect = button.getBoundingClientRect();
            const dropdown = this.$refs.dropdown;
            
            dropdown.style.top = `${rect.bottom + window.scrollY + 5}px`;
            
            // Positionnement horizontal selon l'alignement
            if ('{{ $align }}' === 'right') {
                dropdown.style.left = `${rect.right - dropdown.offsetWidth}px`;
            } else if ('{{ $align }}' === 'center') {
                dropdown.style.left = `${rect.left + (rect.width / 2) - (dropdown.offsetWidth / 2)}px`;
            } else {
                dropdown.style.left = `${rect.left}px`;
            }
            
            // Ajustements pour la visibilité dans la fenêtre
            const viewportWidth = window.innerWidth;
            const dropdownRect = dropdown.getBoundingClientRect();
            
            if (dropdownRect.right > viewportWidth) {
                dropdown.style.left = `${viewportWidth - dropdownRect.width - 10}px`;
            }
            if (dropdownRect.left < 0) {
                dropdown.style.left = '10px';
            }
        });
    }
}" 
@click.away="open = false" 
{{ $attributes }}>
    
    <!-- Dropdown Trigger -->
    @if ($trigger === 'button')
        <button 
            @click="open = !open; if(open) setPosition()" 
            type="button"
            {{ $attributes->only('data-tippy-content') }}
            class="py-1.5 px-2 text-gray-400 hover:text-white rounded-md transition-colors focus:outline-none {{ $triggerClass }}">
            @if ($triggerIcon)
                <i class="fas fa-{{ $triggerIcon }}"></i>
            @endif
            @if ($triggerText)
                <span>{{ $triggerText }}</span>
            @endif
        </button>
    @elseif ($trigger === 'custom')
        <div @click="open = !open; if(open) setPosition()">
            {{ $button }}
        </div>
    @endif

    <!-- Dropdown Content -->
    @if ($teleport)
    <template x-teleport="body">
    @endif
        <div 
            x-show="open"
            x-ref="dropdown"
            @click="{{ $autoClose ? 'open = false' : '' }}"
            x-transition:enter="transition ease-out duration-200" 
            x-transition:enter-start="opacity-0 scale-95" 
            x-transition:enter-end="opacity-100 scale-100" 
            x-transition:leave="transition ease-in duration-175" 
            x-transition:leave-start="opacity-100 scale-100" 
            x-transition:leave-end="opacity-0 scale-95" 
            class="py-1 px-2 {{ $width }} bg-slate-900 bg-opacity-90 border-white border-opacity-20 border rounded-xl shadow-lg" 
            x-cloak
            style="position: absolute; z-index: {{ $zIndex }};">
            
            {{ $slot }}
            
        </div>
    @if ($teleport)
    </template>
    @endif
</div>