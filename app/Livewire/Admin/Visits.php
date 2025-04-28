<?php

namespace App\Livewire\Admin;

use App\Models\Visit;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Visits extends Component
{
    use WithPagination;
    
    public $search = '';
    public $sortField = 'last_visit';
    public $sortDirection = 'desc';
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'last_visit'],
        'sortDirection' => ['except' => 'desc'],
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
    
    public function render()
    {
        // Group visits by IP address and count them
        $visits = DB::table('visits')
            ->select('ip_address', 'country', DB::raw('count(*) as total_visits'), DB::raw('MAX(visited_at) as last_visit'))
            ->when($this->search, function ($query) {
                $query->where('ip_address', 'like', '%' . $this->search . '%')
                      ->orWhere('country', 'like', '%' . $this->search . '%');
            })
            ->groupBy('ip_address', 'country')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.visits', [
            'visits' => $visits,
        ]);
    }
}