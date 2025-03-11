<?php

namespace App\Livewire;

use App\Models\Training;
use App\Models\TrainingType;
use Carbon\Carbon;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class CreateTrainingModal extends ModalComponent
{
    use WireUiActions;    

    public $date;
    public $distance;
    public $duration;
    public $elevation;
    public $notes;
    public $trainingTypeId;
    public $trainingTypes;

    protected $rules = [
        'date' => 'required|date',
        'distance' => 'nullable|numeric',
        'duration' => 'nullable|integer',
        'elevation' => 'nullable|integer',
        'notes' => 'nullable|string',
        'trainingTypeId' => 'required|exists:training_types,id',
    ];

    public function mount()
    {
        $this->trainingTypes = TrainingType::all();    
    }

    public function save()
    {
        $this->validate();

        Training::create([
            'date' => Carbon::parse($this->date),
            'distance' => $this->distance,
            'duration' => $this->duration,
            'elevation' => $this->elevation,
            'notes' => $this->notes,
            'training_type_id' => $this->trainingTypeId,
            'user_id' => Auth::id(),
        ]);

        $this->dispatch('training-created');        
        $this->resetExcept(['trainingTypes']);
        
        $this->notification()->send([
            'icon' => 'success',        
            'title' => 'Success',        
            'description' => 'Training successfully created',        
        ]);
        
        $this->dispatch('closeModal', 'create-training-modal');    
    }

    public function render()
    {
        return view('livewire.create-training-modal');
    }
}