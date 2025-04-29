<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $trigger = 'button',
        public string $triggerClass = '',
        public ?string $triggerIcon = null,
        public ?string $triggerText = null,
        public ?string $align = 'left',
        public ?string $width = 'w-60',
        public bool $teleport = true,
        public ?string $zIndex = '99999'
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dropdown');
    }
}
