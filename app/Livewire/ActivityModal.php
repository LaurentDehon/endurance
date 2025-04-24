<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityModal extends Component
{
    public $activityId;
    public $activity;

    public function mount($id)
    {
        $this->activity = Activity::where('user_id', Auth::id())->findOrFail($id);        
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