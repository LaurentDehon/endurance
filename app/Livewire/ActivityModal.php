<?php

namespace App\Livewire;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class ActivityModal extends ModalComponent
{
    public $activityId;
    public $activity;

    public function mount($id)
    {
        $this->activity = Activity::where('user_id', Auth::id())->findOrFail($id);        
    }

    public function render()
    {        
        return view('livewire.activity-modal');
    }
}