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
            $this->toast()->success($message)->send();
            $this->dispatch('closeModal', 'training-modal');

        } catch (\Exception $e) {
            $this->toast()->error('Error saving training: ' . $e->getMessage())->send();
        }
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

    private function validateNumeric($value)
    {
        return is_numeric($value) && !empty($value) ? $value : 0;
    }

    public function render()
    {
        return view('livewire.training-modal');
    }
}
