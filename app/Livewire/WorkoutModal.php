<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Workout;
use App\Models\WorkoutType;
use Illuminate\Support\Facades\Auth;

class WorkoutModal extends Component
{
    protected $listeners = ['confirmDelete' => 'confirmDelete'];
    
    public $workoutId;
    public $date;
    public $distance;
    public $hours;
    public $minutes;
    public $elevation;
    public $notes;
    public $workoutTypeId;
    public $workoutTypes;
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
        'workoutTypeId' => 'required|exists:workout_types,id',
        'isRecurring' => 'nullable|boolean',
        'recurrenceInterval' => 'nullable|required_if:isRecurring,true|integer|min:1',
        'recurrenceEndDate' => 'nullable|required_if:isRecurring,true|date|after:date',
    ]; 

    // Ajout d'une méthode pour basculer l'état de isRecurring
    public function toggleRecurring()
    {
        $this->isRecurring = !$this->isRecurring;
        
        // Préparer la date de fin par défaut si elle n'est pas définie
        if ($this->isRecurring && empty($this->recurrenceEndDate)) {
            $this->recurrenceEndDate = Carbon::parse($this->date)->addDays(30)->format('Y-m-d');
        }
    }

    public function mount($id = null, $date = null)
    {
        $this->workoutTypes = WorkoutType::all();
        $this->workoutTypeId = $this->workoutTypes->first()->id;        

        // Edit mode
        if ($id) {
            $this->workoutId = $id;
            $workout = Workout::where('user_id', Auth::id())->findOrFail($id);
            
            $this->date = $workout->date->format('Y-m-d');
            $this->distance = $workout->distance;
            $this->hours = floor($workout->duration / 60);
            $this->minutes = $workout->duration % 60;
            $this->elevation = $workout->elevation;
            $this->notes = $workout->notes;
            $this->workoutTypeId = $workout->workout_type_id;
        } 
        // Create mode
        else {
            $this->date = $date ? Carbon::parse($date)->format('Y-m-d') : null;
            // Définir la date de fin par défaut à 30 jours dans le futur
            $this->recurrenceEndDate = $this->date ? Carbon::parse($this->date)->addDays(30)->format('Y-m-d') : null;
        }
    }

    // Méthode pour définir le type d'entraînement sélectionné dans le dropdown
    public function setWorkoutType($id)
    {
        $this->workoutTypeId = $id;
    }

    public function save()
    {
        $this->validate();

        $this->distance = $this->validateNumeric($this->distance);
        $this->elevation = $this->validateNumeric($this->elevation);

        $baseData = [
            'date' => Carbon::parse($this->date),
            'distance' => $this->distance,
            'duration' => (is_numeric($this->hours) ? $this->hours : 0) * 60 + (is_numeric($this->minutes) ? $this->minutes : 0),
            'elevation' => $this->elevation,
            'notes' => $this->notes,
            'workout_type_id' => $this->workoutTypeId,
            'user_id' => Auth::id(),
        ];

        try {
            if ($this->isRecurring) {
                $startDate = Carbon::parse($this->date);
                $endDate = Carbon::parse($this->recurrenceEndDate);
                $interval = $this->recurrenceInterval;

                $currentDate = $startDate->copy();

                while ($currentDate <= $endDate) {
                    Workout::create(array_merge($baseData, ['date' => $currentDate]));
                    $currentDate = $currentDate->addDays((int)$interval);
                }

                $message = 'Recurring workouts created successfully';
            } else {
                if ($this->workoutId) {
                    $workout = Workout::where('user_id', Auth::id())->findOrFail($this->workoutId);
                    $workout->update($baseData);
                    $message = 'Workout successfully updated';
                } 
                else {
                    Workout::create($baseData); // Simplified since baseData already includes date
                    $message = 'Workout successfully created';
                }
            }

            $this->dispatch('workout-created');
            $this->dispatch('reload-tooltips');

            $this->dispatch('toast', $message, 'success');
            $this->dispatch('closeModal', 'workout-modal');

        } catch (\Exception $e) {
            $this->dispatch('toast', $e->getMessage(), 'error');
        }
    }

    public function delete()
    {
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm deletion',
            'message' => 'Are you sure you want to delete this workout?<br>This action cannot be undone.',
            'confirmButtonText' => 'Confirm',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDelete',
            'icon' => 'trash-alt',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDelete()
    {
        $workout = Workout::where('user_id', Auth::id())
            ->findOrFail($this->workoutId);
            
        $workout->delete();

        $this->dispatch('toast', 'Workout deleted successfully', 'success');
        $this->dispatch('closeModal', 'workout-modal');
        $this->dispatch('workout-created');
    }

    private function validateNumeric($value)
    {
        return is_numeric($value) && !empty($value) ? $value : 0;
    }

    public function close()
    {
        $this->dispatch('closeModal', 'workout-modal');
    }

    public function render()
    {
        return view('livewire.workout-modal');
    }
}
