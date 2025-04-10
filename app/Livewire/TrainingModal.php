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

    protected $listeners = ['confirmDelete' => 'confirmDelete'];
    
    public $trainingId;
    public $date;
    public $distance;
    public $hours;
    public $minutes;
    public $elevation;
    public $notes;
    public $trainingTypeId;
    public $trainingTypes;
    public $isRecurring = false;
    public $recurrenceInterval = 7;
    public $recurrenceEndDate;

    protected $rules = [
        'date' => 'required|date',
        'distance' => 'nullable|numeric',
        'hours' => 'nullable|integer|min:0',
        'minutes' => 'nullable|integer|between:0,59',
        'elevation' => 'nullable|integer',
        'notes' => 'nullable|string',
        'trainingTypeId' => 'required|exists:training_types,id',
        'isRecurring' => 'nullable|boolean',
        'recurrenceInterval' => 'nullable|required_if:isRecurring,true|integer|min:1',
        'recurrenceEndDate' => 'nullable|required_if:isRecurring,true|date|after:date',
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

        $this->distance = $this->validateNumeric($this->distance);
        $this->elevation = $this->validateNumeric($this->elevation);

        $baseData = [
            'distance' => $this->distance,
            'duration' => (is_numeric($this->hours) ? $this->hours : 0) * 60 + (is_numeric($this->minutes) ? $this->minutes : 0),
            'elevation' => $this->elevation,
            'notes' => $this->notes,
            'training_type_id' => $this->trainingTypeId,
            'user_id' => Auth::id(),
        ];

        try {
            if ($this->isRecurring) {
                $startDate = Carbon::parse($this->date);
                $endDate = Carbon::parse($this->recurrenceEndDate);
                $interval = $this->recurrenceInterval;

                $currentDate = $startDate->copy();

                while ($currentDate <= $endDate) {
                    Training::create(array_merge($baseData, ['date' => $currentDate]));
                    $currentDate = $currentDate->addDays((int)$interval);
                }

                $message = 'Recurring trainings created successfully';
            } else {
                if ($this->trainingId) {
                    $training = Training::where('user_id', Auth::id())->findOrFail($this->trainingId);
                    $training->update($baseData);
                    $message = 'Training successfully updated';
                } 
                else {
                    Training::create(array_merge($baseData, ['date' => Carbon::parse($this->date)]));
                    $message = 'Training successfully created';
                }
            }

            $this->dispatch('training-created');
            $this->dispatch('toast', $message, 'success');
            $this->dispatch('closeModal', 'training-modal');

        } catch (\Exception $e) {
            $this->dispatch('toast', $e->getMessage(), 'error');
        }
    }

    public function delete()
    {
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm deletion',
            'message' => 'Are you sure you want to delete this training?<br>This action cannot be undone.',
            'confirmButtonText' => 'Confirm',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDelete',
            'icon' => 'trash-alt',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDelete()
    {
        $training = Training::where('user_id', Auth::id())
            ->findOrFail($this->trainingId);
            
        $training->delete();

        $this->dispatch('toast', 'Training deleted successfully', 'success');
        $this->dispatch('closeModal', 'training-modal');
        $this->dispatch('training-created');
    }

    private function validateNumeric($value)
    {
        return is_numeric($value) && !empty($value) ? $value : 0;
    }

    public function close()
    {
        $this->dispatch('closeModal', 'training-modal');
    }

    public function render()
    {
        return view('livewire.training-modal');
    }
}
