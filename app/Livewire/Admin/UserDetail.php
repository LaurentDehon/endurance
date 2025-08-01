<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class UserDetail extends Component
{    
    public User $user;
    public $email = [];
    public $showEmailForm = false;
    
    protected $listeners = ['confirmDeleteUser', 'confirmSendEmail', 'confirmResetStrava', 'confirmDisconnectStrava', 'confirmBanIp'];
    
    protected $rules = [
        'email.subject' => 'required|string|max:255',
        'email.message' => 'required|string',
    ];
    
    public function mount($userId)
    {
        $this->refreshUserData($userId);
    }
    
    // Method to refresh user data with counts
    private function refreshUserData($userId = null)
    {
        $query = User::with(['activities', 'workouts', 'weeks'])
            ->withCount(['activities', 'workouts', 'weeks']);
            
        if ($userId) {
            $this->user = $query->where('id', $userId)->firstOrFail();
        } else if (isset($this->user->id)) {
            $this->user = $query->where('id', $this->user->id)->firstOrFail();
        } else {
            abort(404, 'User not found');
        }
    }
    
    public function toggleEmailForm()
    {
        $this->email = [
            'subject' => __('admin.modal.email.default_subject', ['app_name' => config('app.name')]),
            'message' => __('admin.modal.email.default_message', ['name' => $this->user->name]) . "\n\n",
        ];

        // Use EmailModal
        $this->dispatch('openModal', 'modal.email-modal', ['userId' => $this->user->id, 'email' => $this->email]);
    }
    
    public function sendEmail()
    {
        $this->validate();
        
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.user_detail.confirm.email_sending'),
            'message' => __('admin.user_detail.confirm.email_message', ['name' => $this->user->name, 'subject' => $this->email['subject']]),
            'confirmButtonText' => __('admin.user_detail.confirm.send_email'),
            'cancelButtonText' => __('admin.user_detail.confirm.cancel'),
            'confirmAction' => 'confirmSendEmail',
            'params' => [],
            'icon' => 'envelope',
            'iconColor' => 'blue'
        ]);
    }
    
    public function confirmSendEmail()
    {
        // Logic to send the email
        Mail::raw($this->email['message'], function ($message) {
            $message->to($this->user->email)
                ->subject($this->email['subject']);
        });
        
        $this->dispatch('closeModal');
        $this->dispatch('toast', __('admin.user_detail.messages.email_sent', ['name' => $this->user->name]), 'success');
    }
    
    public function resetStravaConnection()
    {
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.user_detail.confirm.reset_strava'),
            'message' => __('admin.user_detail.confirm.reset_strava_message', ['name' => $this->user->name]),
            'confirmButtonText' => __('admin.user_detail.confirm.reset_connection'),
            'cancelButtonText' => __('admin.user_detail.confirm.cancel'),
            'confirmAction' => 'confirmResetStrava',
            'params' => [],
            'icon' => 'running',
            'iconColor' => 'orange'
        ]);
    }
    
    public function confirmResetStrava()
    {
        // Faire expirer le token pour forcer une reconnexion
        $this->user->update(['strava_expires_at' => now()->timestamp]);
        $this->dispatch('toast', __('admin.user_detail.messages.strava_reset', ['name' => $this->user->name]), 'success');
    }
    
    public function disconnectStrava()
    {
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.user_detail.confirm.disconnect_strava'),
            'message' => __('admin.user_detail.confirm.disconnect_strava_message', ['name' => $this->user->name]),
            'confirmButtonText' => __('admin.user_detail.confirm.disconnect_connection'),
            'cancelButtonText' => __('admin.user_detail.confirm.cancel'),
            'confirmAction' => 'confirmDisconnectStrava',
            'params' => [],
            'icon' => 'times-circle',
            'iconColor' => 'red'
        ]);
    }
    
    public function confirmDisconnectStrava()
    {
        // Supprimer complètement la connexion Strava
        $this->user->update([
            'strava_token' => null,
            'strava_refresh_token' => null,
            'strava_expires_at' => null,
            'last_sync_at' => null
        ]);
        $this->dispatch('toast', __('admin.user_detail.messages.strava_disconnected', ['name' => $this->user->name]), 'success');
    }
    
    public function deleteUser()
    {
        // Check if user is admin
        if ($this->user->is_admin) {
            $this->dispatch('toast', __('admin.user_detail.messages.cannot_delete_admin'), 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.user_detail.confirm.delete_user'),
            'message' => __('admin.user_detail.confirm.delete_user_message', ['name' => $this->user->name]),
            'confirmButtonText' => __('admin.user_detail.confirm.delete_user_button'),
            'cancelButtonText' => __('admin.user_detail.confirm.cancel'),
            'confirmAction' => 'confirmDeleteUser',
            'params' => [],
            'icon' => 'user-slash',
            'iconColor' => 'red'
        ]);
    }
    
    public function confirmDeleteUser()
    {
        // Additional verification to avoid deleting administrators
        if ($this->user->is_admin) {
            $this->dispatch('toast', __('admin.user_detail.messages.cannot_delete_admin'), 'error');
            return;
        }
        
        $name = $this->user->name;
        
        $this->user->weeks()->delete();
        $this->user->activities()->delete();
        $this->user->workouts()->delete();

        $this->user->delete();
        
        $this->dispatch('toast', __('admin.user_detail.messages.user_deleted', ['name' => $name]), 'success');
        return redirect()->route('admin');
    }

    public function toggleAdmin()
    {
        $this->user->update(['is_admin' => !$this->user->is_admin]);
        $status = $this->user->is_admin ? 'admin_granted' : 'admin_revoked';
        $this->dispatch('toast', __('admin.user_detail.messages.' . $status, ['name' => $this->user->name]), 'success');
    }

    public function verifyEmail()
    {
        $this->user->update(['email_verified_at' => $this->user->email_verified_at ? null : now()]);
        $status = $this->user->email_verified_at ? 'email_verified' : 'email_unverified';
        $this->dispatch('toast', __('admin.user_detail.messages.' . $status, ['name' => $this->user->name]), 'success');
    }

    public function sendResetPassword()
    {
        $token = Password::createToken($this->user);
        $this->user->sendPasswordResetNotification($token);
        $this->dispatch('toast', __('admin.user_detail.messages.password_reset_sent', ['name' => $this->user->name]), 'success');
    }

    public function resendVerificationEmail()
    {
        $this->user->sendEmailVerificationNotification();
        $this->dispatch('toast', __('admin.user_detail.messages.verification_email_sent', ['name' => $this->user->name]), 'success');
    }

    public function banIpAddress()
    {
        // Check if the user has a registered IP address
        if (!$this->user->last_ip_address) {
            $this->dispatch('toast', __('admin.user_detail.messages.no_ip_available'), 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => __('admin.user_detail.confirm.ban_ip'),
            'message' => __('admin.user_detail.confirm.ban_ip_message', ['ip' => $this->user->last_ip_address, 'name' => $this->user->name]),
            'confirmButtonText' => __('admin.user_detail.confirm.ban_ip_button'),
            'cancelButtonText' => __('admin.user_detail.confirm.cancel'),
            'confirmAction' => 'confirmBanIp',
            'params' => [],
            'icon' => 'ban',
            'iconColor' => 'red'
        ]);
    }
    
    public function confirmBanIp()
    {
        // Additional verification that the IP address exists
        if (!$this->user->last_ip_address) {
            $this->dispatch('toast', __('admin.user_detail.messages.no_ip_available'), 'error');
            return;
        }
        
        // Check if the IP is already banned
        if (\App\Models\BannedIp::isIpBanned($this->user->last_ip_address)) {
            $this->dispatch('toast', __('admin.user_detail.messages.ip_already_banned'), 'warning');
            return;
        }
        
        // Create ban record
        \App\Models\BannedIp::create([
            'ip_address' => $this->user->last_ip_address,
            'reason' => 'Banned from user profile by administrator',
            'banned_by' => Auth::id(),
        ]);
        
        $this->dispatch('toast', __('admin.user_detail.messages.ip_banned', ['ip' => $this->user->last_ip_address]), 'success');
    }

    public function render()
    {
        // Ensure we have fresh data when rendering
        if (isset($this->user->id)) {
            $this->refreshUserData();
        }
        
        return view('livewire.admin.user-detail');
    }
}