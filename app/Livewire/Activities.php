<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Activities extends Component
{
    use WithPagination;
    
    protected $listeners = [
        'confirmDeleteAll', 
        'confirmDelete',
        'activity-deleted' => '$refresh',
    ];

    public $search = '';
    public $sortField = 'start_date';
    public $sortDirection = 'desc';
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'start_date'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 25]
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
        
        // Dispatch event to reload tooltips after sorting
        $this->dispatch('reload-tooltips');
    }
    
    // Intercept search model updates to reload tooltips
    public function updatedSearch()
    {
        $this->resetPage();
        $this->dispatch('reload-tooltips');
    }
    
    // Intercept perPage model updates to reload tooltips
    public function updatedPerPage()
    {
        $this->resetPage();
        $this->dispatch('reload-tooltips');
    }

    // Function to trigger delete all confirmation
    public function deleteAll()
    {
        $this->dispatch('openConfirmModal', [
            'title' => __('activities.delete_all_title'),
            'message' => __('activities.delete_all_message'),
            'confirmAction' => 'confirmDeleteAll',
        ]);
    }

    // Function to delete all activities after confirmation
    public function confirmDeleteAll()
    {
        Activity::where('user_id', Auth::id())->delete();
        
        // Invalider explicitement les caches du calendrier
        $cacheKeys = [
            'calendar-activities-' . Auth::id() . '-' . now()->year,
            'calendar-months-' . Auth::id() . '-' . now()->year,
            'calendar-month-stats-' . Auth::id() . '-' . now()->year,
            'calendar-year-stats-' . Auth::id() . '-' . now()->year,
            'calendar-weeks-' . Auth::id() . '-' . now()->year
        ];
        
        foreach ($cacheKeys as $key) {
            \Illuminate\Support\Facades\Cache::forget($key);
        }
        
        $this->dispatch('toast', [
            'type' => 'success',
            'message' => __('activities.delete_all_success')
        ]);
        
        $this->dispatch('reload-tooltips');
    }

    public function render()
    {
        return view('livewire.activities', [
            'activities' => Activity::where('user_id', Auth::id())
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}