<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;
    
    protected $listeners = ['confirmDeleteUser', 'confirmDeleteUnverifiedUsers'];

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
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

    public function deleteUnverifiedUsers()
    {
        $cutoffDate = now()->subDays(5);
        
        $count = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoffDate)
            ->where('is_admin', false)
            ->where('id', '!=', auth()->id())
            ->count();
            
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.users.confirm.delete_unverified_title'),
            'message' => __('admin.users.confirm.delete_unverified_message', ['count' => $count]),
            'confirmButtonText' => __('admin.users.confirm.delete_button'),
            'cancelButtonText' => __('admin.users.confirm.cancel'),
            'confirmAction' => 'confirmDeleteUnverifiedUsers',
            'icon' => 'user-times',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDeleteUnverifiedUsers()
    {
        $cutoffDate = now()->subDays(5);
        
        $deletedCount = User::whereNull('email_verified_at')
            ->where('created_at', '<', $cutoffDate)
            ->where('is_admin', false) // Sécurité supplémentaire pour ne pas supprimer les admins
            ->where('id', '!=', auth()->id()) // Ne jamais supprimer l'utilisateur connecté
            ->delete();
        
        $this->dispatch('toast', __('admin.users.messages.users_deleted', ['count' => $deletedCount]), 'success');
        
        // Rafraîchir la pagination si nécessaire
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        // Compter les utilisateurs non vérifiés de plus de 5 jours
        $unverifiedOldUsers = User::whereNull('email_verified_at')
            ->where('created_at', '<', now()->subDays(5))
            ->where('is_admin', false)
            ->where('id', '!=', auth()->id()) // Ne jamais compter l'utilisateur connecté
            ->count();

        return view('livewire.admin.users', [
            'users' => $users,
            'unverifiedOldUsersCount' => $unverifiedOldUsers,
        ]);
    }
}