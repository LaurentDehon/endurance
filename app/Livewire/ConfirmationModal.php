<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ConfirmationModal extends Component
{
    public $showModal = false;
    public $title = "Confirmer";
    public $message = "Êtes-vous sûr de vouloir effectuer cette action ?";
    public $confirmButtonText = "Confirmer";
    public $cancelButtonText = "Annuler";
    public $confirmAction = '';
    public $params = [];
    public $icon = 'exclamation-triangle';
    public $iconColor = 'red';
    
    #[On('openConfirmModal')]
    public function open($options = [])
    {
        $this->showModal = true;
        $this->title = $options['title'] ?? $this->title;
        $this->message = $options['message'] ?? $this->message;
        $this->confirmButtonText = $options['confirmButtonText'] ?? $this->confirmButtonText;
        $this->cancelButtonText = $options['cancelButtonText'] ?? $this->cancelButtonText;
        $this->confirmAction = $options['confirmAction'] ?? '';
        $this->params = $options['params'] ?? [];
        $this->icon = $options['icon'] ?? $this->icon;
        $this->iconColor = $options['iconColor'] ?? $this->iconColor;
    }
    
    public function confirm()
    {
        $this->showModal = false;
        if ($this->confirmAction) {
            $this->dispatch($this->confirmAction, $this->params);
        }
    }
    
    public function cancel()
    {
        $this->showModal = false;
    }
    
    public function render()
    {
        return view('livewire.confirmation-modal');
    }
}
