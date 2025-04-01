<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Password;

class Admin extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortBy' => ['except' => 'name'],
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

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        $user->weeks()->delete();
        $user->activities()->delete();
        $user->trainings()->delete();

        $user->delete();
    }

    public function toggleAdmin($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['is_admin' => !$user->is_admin]);
    }

    public function verifyEmail($userId)
    {
        $user = User::findOrFail($userId);
        $user->update(['email_verified_at' => $user->email_verified_at ? null : now()]);
    }

    public function sendResetPassword($userId)
    {
        $user = User::findOrFail($userId);
        $token = Password::createToken($user);
        $user->sendPasswordResetNotification($token);
    }

    public function resendVerificationEmail($userId)
    {
        $user = User::findOrFail($userId);
        $user->sendEmailVerificationNotification();
    }

    public function render()
    {
        $users = User::query()
            ->withCount('activities')
            ->withCount('trainings')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin', [
            'users' => $users,
        ]);
    }
}