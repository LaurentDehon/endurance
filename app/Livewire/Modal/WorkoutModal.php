<?php

namespace App\Livewire\Modal;

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
                $createdDates = [];

                while ($currentDate <= $endDate) {
                    $workoutDate = $currentDate->copy();
                    Workout::create(array_merge($baseData, ['date' => $workoutDate]));
                    $createdDates[] = $workoutDate->format('Y-m-d');
                    $currentDate = $currentDate->addDays((int)$interval);
                }

                foreach ($createdDates as $createdDate) {
                    $this->dispatch('workout-saved', date: $createdDate);
                }

                $this->dispatch('workout-created');
                $message = __('workouts.recurring_created_success');
            } else {
                if ($this->workoutId) {
                    $workout = Workout::where('user_id', Auth::id())->findOrFail($this->workoutId);
                    $workout->update($baseData);
                    $message = __('workouts.updated_success');
                    
                    // Stocker la date pour l'optimisation
                    session(['last_workout_date' => $this->date]);
                    $this->dispatch('workout-updated');
                } 
                else {
                    Workout::create($baseData);
                    $message = __('workouts.created_success');
                    
                    // Stocker la date pour l'optimisation
                    session(['last_workout_date' => $this->date]);
                    $this->dispatch('workout-created');
                }
                
                $this->dispatch('workout-saved', date: $this->date);
            }

            $this->dispatch('toast', $message, 'success');
            $this->dispatch('closeModal', 'workout-modal');

        } catch (\Exception $e) {
            $this->dispatch('toast', $e->getMessage(), 'error');
        }
    }

    public function delete()
    {
        $this->dispatch('openConfirmModal', [
            'title' => __('workouts.confirm_deletion'),
            'message' => __('workouts.delete_confirmation_message'),
            'confirmButtonText' => __('workouts.confirm'),
            'cancelButtonText' => __('workouts.cancel'),
            'confirmAction' => 'confirmDelete',
            'icon' => 'trash-alt',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDelete()
    {
        $workout = Workout::where('user_id', Auth::id())
            ->findOrFail($this->workoutId);
        
        // Stocker la date avant suppression pour l'optimisation
        $workoutDate = $workout->date->format('Y-m-d');
        session(['last_workout_date' => $workoutDate]);
            
        $workout->delete();

        $this->dispatch('toast', __('workouts.deleted_success'), 'success');
        $this->dispatch('closeModal', 'workout-modal');
        $this->dispatch('workout-deleted');
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
        return view('livewire.modal.workout-modal');
    }
}
