<?php

namespace App\Livewire\Modal;

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
            'title' => __('activities.confirm_deletion'),
            'message' => __('activities.delete_confirmation_message'),
            'confirmButtonText' => __('activities.confirm'),
            'cancelButtonText' => __('activities.cancel'),
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

        $this->dispatch('toast', __('activities.deleted_success'), 'success');
        $this->dispatch('closeModal', 'activity-modal');
        $this->dispatch('activity-deleted');
    }

    public function close()
    {
        $this->dispatch('closeModal', 'activity-modal');
    }

    public function render()
    {        
        return view('livewire.modal.activity-modal');
    }
}