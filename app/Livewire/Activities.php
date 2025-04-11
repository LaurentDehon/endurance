<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Activities extends Component
{
    use WithPagination;
    
    protected $listeners = ['confirmDeleteAll', 'confirmDelete'];

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
    }

    public function delete($activityId)
    {        
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm deletion',
            'message' => 'Are you sure you want to delete this activity?<br>This action cannot be undone.',
            'confirmButtonText' => 'Confirm',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDelete',
            'params' => [$activityId],
            'icon' => 'trash-alt',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDelete($params)
    {
        $activityId = $params[0];
        Activity::find($activityId)->delete();

        $this->dispatch('toast', 'Activity deleted successfully', 'success');
    }

    public function deleteAll()
    {
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm deletion',
            'message' => 'Are you sure you want to delete all your activities?<br> This action cannot be undone.',
            'confirmButtonText' => 'Confirm',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmDeleteAll',
            'icon' => 'exclamation-triangle',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDeleteAll()
    {
        Activity::where('user_id', Auth::id())->delete();

        $this->dispatch('toast', 'All activities were successfully deleted', 'success');
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