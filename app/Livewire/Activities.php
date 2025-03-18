<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Activity;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use TallStackUi\Traits\Interactions;

class Activities extends Component
{
    use WithPagination, Interactions;

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
        $this->dialog()
            ->question('Warning!', 'Are you sure?')
            ->confirm(method: 'confirmDelete', params: [$activityId])
            ->send();
    }

    public function confirmDelete(array $params)
    {
        $activityId = $params[0];
        Activity::find($activityId)->delete();

        $this->toast()->success('Training deleted successfully')->send();
    }

    public function deleteAll()
    {
        $this->dialog()
            ->question('Warning!', 'Are you sure?')
            ->confirm(method: 'confirmDeleteAll')
            ->send();
    }

    public function confirmDeleteAll()
    {
        Activity::truncate();

        $this->toast()->success('All activities deleted successfully')->send();
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