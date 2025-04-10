<?php

namespace App\Livewire;

use App\Models\Week;
use App\Models\WeekType;
use Livewire\Component;
use TallStackUi\Traits\Interactions;

class WeekTypeModal extends Component
{
    use Interactions;

    public $weekId;
    public $currentTypeId;
    public $weekTypes;

    public function mount($weekId = null, $currentTypeId = null)
    {
        $this->weekId = $weekId;
        $this->currentTypeId = $currentTypeId;

        $this->weekTypes = WeekType::all();
    }

    public function selectWeekType($typeId = null)
    {
        try {
            $week = Week::findOrFail($this->weekId);
            $week->update([
                'week_type_id' => $typeId
            ]);
            
            $typeName = $typeId ? WeekType::find($typeId)->name : 'None';
            $this->dispatch('toast', 'Week type updated to ' . strtolower($typeName), 'success');
            $this->dispatch('refresh');
            $this->dispatch('closeModal');
            
        } catch (\Exception $e) {
            $this->dispatch('toast', $e->getMessage(), 'error');
        }
    }

    public function render()
    {
        return view('livewire.week-type-modal');
    }
}
