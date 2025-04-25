<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityModal extends Component
{
    protected $listeners = ['confirmDelete'];
    
    public $activity;

    public function mount($id)
    {
        $this->activity = Activity::where('user_id', Auth::id())->findOrFail($id);        
    }

    public function delete()
    {
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm deletion',
            'message' => 'Are you sure you want to delete this activity?<br>This action cannot be undone.',
            'confirmButtonText' => 'Confirm',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDelete',
            'icon' => 'trash-alt',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDelete()
    {
        Activity::where('user_id', Auth::id())
            ->where('id', $this->activity->id)
            ->delete();

        $this->dispatch('toast', 'Activity deleted successfully', 'success');
        $this->dispatch('closeModal', 'activity-modal');
        $this->dispatch('refresh-calendar');
    }

    public function close()
    {
        $this->dispatch('closeModal', 'activity-modal');
    }

    public function render()
    {        
        return view('livewire.activity-modal');
    }
}