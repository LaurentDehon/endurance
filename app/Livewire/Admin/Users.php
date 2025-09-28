<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\BannedIp;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;
    
    protected $listeners = ['confirmDeleteUser', 'confirmDeleteUnverifiedUsers', 'confirmDeleteSingleUser', 'confirmDeleteUserAndBanIp'];

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

    public function deleteSingleUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Vérifications de sécurité
        if ($user->is_admin) {
            $this->dispatch('toast', __('admin.user_detail.messages.cannot_delete_admin'), 'error');
            return;
        }
        
        if ($user->id === auth()->id()) {
            $this->dispatch('toast', __('admin.user_detail.messages.cannot_delete_self'), 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.user_detail.confirm.delete_user'),
            'message' => __('admin.user_detail.confirm.delete_user_message', ['name' => $user->name]),
            'confirmButtonText' => __('admin.user_detail.confirm.delete_user_button'),
            'cancelButtonText' => __('admin.user_detail.confirm.cancel'),
            'confirmAction' => 'confirmDeleteSingleUser',
            'params' => [$userId],
            'icon' => 'user-times',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDeleteSingleUser($params)
    {
        $userId = $params[0];
        $user = User::findOrFail($userId);
        
        // Vérifications de sécurité
        if ($user->is_admin || $user->id === auth()->id()) {
            return;
        }
        
        $userName = $user->name;
        $user->delete();
        
        $this->dispatch('toast', __('admin.user_detail.messages.user_deleted', ['name' => $userName]), 'success');
        
        // Rafraîchir la pagination si nécessaire
        $this->resetPage();
    }

    public function deleteUserAndBanIp($userId)
    {
        $user = User::findOrFail($userId);
        
        // Vérifications de sécurité
        if ($user->is_admin) {
            $this->dispatch('toast', __('admin.user_detail.messages.cannot_delete_admin'), 'error');
            return;
        }
        
        if ($user->id === auth()->id()) {
            $this->dispatch('toast', __('admin.user_detail.messages.cannot_delete_self'), 'error');
            return;
        }
        
        // Vérifier si l'utilisateur a une adresse IP connue
        $lastIp = $user->last_ip_address;
        
        if (!$lastIp) {
            $this->dispatch('toast', __('admin.users.messages.no_ip_available'), 'error');
            return;
        }
        
        // Vérifier si l'IP n'est pas déjà bannie
        if (BannedIp::isIpBanned($lastIp)) {
            $this->dispatch('toast', __('admin.users.messages.ip_already_banned'), 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.users.confirm.delete_and_ban_title'),
            'message' => __('admin.users.confirm.delete_and_ban_message', [
                'name' => $user->name,
                'ip' => $lastIp
            ]),
            'confirmButtonText' => __('admin.users.confirm.delete_and_ban_button'),
            'cancelButtonText' => __('admin.users.confirm.cancel'),
            'confirmAction' => 'confirmDeleteUserAndBanIp',
            'params' => [$userId],
            'icon' => 'ban',
            'iconColor' => 'red'
        ]);
    }

    public function confirmDeleteUserAndBanIp($params)
    {
        $userId = $params[0];
        $user = User::findOrFail($userId);
        
        // Vérifications de sécurité
        if ($user->is_admin || $user->id === auth()->id()) {
            return;
        }
        
        $userName = $user->name;
        $lastIp = $user->last_ip_address;
        
        // Bannir l'IP si elle existe et n'est pas déjà bannie
        if ($lastIp && !BannedIp::isIpBanned($lastIp)) {
            BannedIp::create([
                'ip_address' => $lastIp,
                'reason' => __('admin.users.messages.ban_reason_user_deletion', ['name' => $userName]),
                'banned_by' => auth()->id(),
            ]);
        }
        
        // Supprimer l'utilisateur
        $user->delete();
        
        $this->dispatch('toast', __('admin.users.messages.user_deleted_and_ip_banned', [
            'name' => $userName,
            'ip' => $lastIp
        ]), 'success');
        
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