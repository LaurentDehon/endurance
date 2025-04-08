<?php

namespace App\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class CustomModal extends Component
{
    public $show = false;
    public $component = null;
    public $componentAttributes = [];
    public $maxWidth = '2xl';
    public $closeable = true;
    
    protected $listeners = [
        'openModal' => 'openModal',
        'closeModal' => 'closeModal',
    ];

    public function openModal($component, $attributes = [])
    {
        $this->component = $component;
        $this->componentAttributes = $attributes;
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->component = null;
        $this->componentAttributes = [];
    }

    public function getMaxWidthClass(): string
    {
        return [
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            'full' => 'sm:max-w-full',
        ][$this->maxWidth] ?? 'sm:max-w-2xl';
    }
    
    public function render(): View
    {
        return view('livewire.custom-modal');
    }
}
