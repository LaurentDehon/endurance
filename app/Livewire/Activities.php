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