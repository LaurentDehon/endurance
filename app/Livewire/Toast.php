<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Toast extends Component
{
    public $toasts = [];
    
    // Intercepter les événements 'toast' émis par les composants Livewire
    #[On('toast')]
    public function showToast($message, $type = 'info', $duration = 4000)
    {
        // Vérifier si le premier argument est un tableau (nouveau format)
        if (is_array($message) && isset($message['message'])) {
            $data = $message;
            $message = $data['message'];
            $type = $data['type'] ?? 'info';
            $duration = $data['duration'] ?? 4000;
        }
        
        if (is_string($message)) {
            $this->addToast($message, $type, $duration);
        }
    }
    
    // Méthode commune pour ajouter un toast
    protected function addToast($message, $type = 'info', $duration = 4000)
    {
        $toastId = uniqid();
        
        $this->toasts[] = [
            'id' => $toastId,
            'message' => $message,
            'type' => $type,
            'duration' => $duration,
            'expiry' => now()->addMilliseconds($duration)->timestamp
        ];        
    }
    
    #[On('remove-toast')]
    public function removeToast($toastId)
    {
        $this->toasts = array_filter($this->toasts, function($toast) use ($toastId) {
            return $toast['id'] !== $toastId;
        });
    }
    
    public function removeExpiredToasts()
    {
        $currentTime = now()->timestamp;
        $this->toasts = array_filter($this->toasts, function($toast) use ($currentTime) {
            return !isset($toast['expiry']) || $toast['expiry'] > $currentTime;
        });
    }
    
    public function render()
    {
        // Supprimer les toasts expirés
        $this->removeExpiredToasts();
        
        // Intercepter les messages toast stockés en session à chaque rendu
        if (session()->has('toast')) {
            $sessionToast = session('toast');
            $message = $sessionToast['message'] ?? null;
            $type = $sessionToast['type'] ?? 'info';
            $duration = $sessionToast['duration'] ?? 4000;
            
            if ($message) {
                $this->addToast($message, $type, $duration);
            }
            
            session()->forget('toast');
        }
        
        return view('livewire.toast');
    }
}
