<?php

namespace App\Livewire;

use Carbon\Carbon;
use LivewireUI\Modal\ModalComponent;
use App\Models\Training;
use App\Models\TrainingType;
use Illuminate\Support\Facades\Auth;
use TallStackUi\Traits\Interactions;

class TrainingModal extends ModalComponent
{
    use Interactions; 

    public $trainingId;
    public $date;
    public $distance = 10;
    public $hours = 1;
    public $minutes = 0;
    public $elevation = 0;
    public $notes;
    public $trainingTypeId;
    public $trainingTypes;

    protected $rules = [
        'date' => 'required|date',
        'distance' => 'nullable|numeric',
        'hours' => 'nullable|integer|min:0',
        'minutes' => 'nullable|integer|between:0,59',
        'elevation' => 'nullable|integer',
        'notes' => 'nullable|string',
        'trainingTypeId' => 'required|exists:training_types,id',
    ]; 

    public function mount($id = null, $date = null)
    {
        $this->trainingTypes = TrainingType::all();
        $this->trainingTypeId = $this->trainingTypes->first()->id;        

        // Edit mode
        if ($id) {
            $this->trainingId = $id;
            $training = Training::where('user_id', Auth::id())->findOrFail($id);
            
            $this->date = $training->date->format('Y-m-d');
            $this->distance = $training->distance;
            $this->hours = floor($training->duration / 60);
            $this->minutes = $training->duration % 60;
            $this->elevation = $training->elevation;
            $this->notes = $training->notes;
            $this->trainingTypeId = $training->training_type_id;
        } 
        // Create mode
        else {
            $this->date = $date ? Carbon::parse($date)->format('Y-m-d') : null;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'date' => Carbon::parse($this->date),
            'distance' => $this->distance,
            'duration' => ($this->hours ?? 0) * 60 + ($this->minutes ?? 0),
            'elevation' => $this->elevation,
            'notes' => $this->notes,
            'training_type_id' => $this->trainingTypeId,
            'user_id' => Auth::id(),
        ];

        // Update existing training
        if ($this->trainingId) {
            $training = Training::where('user_id', Auth::id())->findOrFail($this->trainingId);
            $training->update($data);
            $message = 'Training successfully updated';
        } 
        // Create new training
        else {
            Training::create($data);
            $message = 'Training successfully created';
        }

        $this->dispatch('training-created');
        $this->toast()->success($message)->send();
        $this->dispatch('closeModal', 'training-modal');
    }

    public function delete()
    {
        $this->dialog()
            ->question('Warning!', 'Are you sure?')
            ->confirm(method: 'confirmed')
            ->send();        
    }

    public function confirmed()
    {
        try {
            $training = Training::where('user_id', Auth::id())
                ->findOrFail($this->trainingId);
            
            $training->delete();
            
            $this->dispatch('training-created');
            $this->toast()->success('Training deleted successfully')->send();
            $this->dispatch('closeModal', 'training-modal');
            
        } catch (\Exception $e) {
            $this->toast()->error('Error deleting training : ' . $e->getMessage())->send();
        }
    }

    public function render()
    {
        return view('livewire.training-modal');
    }
}
