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
    
    protected $listeners = ['confirmDeleteUser', 'confirmSendEmail', 'confirmResetStrava', 'confirmBanIp'];
    
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
            'subject' => 'Message from ' . config('app.name'),
            'message' => "Hello {$this->user->name},\n\n",
        ];

        // Use EmailModal
        $this->dispatch('openModal', 'modal.email-modal', ['userId' => $this->user->id, 'email' => $this->email]);
    }
    
    public function sendEmail()
    {
        $this->validate();
        
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm Email Sending',
            'message' => "Are you sure you want to send an email to <strong>{$this->user->name}</strong> with subject: '{$this->email['subject']}'?",
            'confirmButtonText' => 'Send Email',
            'cancelButtonText' => 'Cancel',
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
        $this->dispatch('toast', 'Email sent successfully to ' . $this->user->name, 'success');
    }
    
    public function resetStravaConnection()
    {
        $this->dispatch('openConfirmModal', [
            'title' => 'Reset Strava Connection',
            'message' => "Are you sure you want to force <strong>{$this->user->name}</strong> to reconnect to Strava?",
            'confirmButtonText' => 'Reset Connection',
            'cancelButtonText' => 'Cancel',
            'confirmAction' => 'confirmResetStrava',
            'params' => [],
            'icon' => 'running',
            'iconColor' => 'orange'
        ]);
    }
    
    public function confirmResetStrava()
    {
        $this->user->update(['strava_expires_at' => now()->timestamp]);
        $this->dispatch('toast', 'Strava connection has been reset for ' . $this->user->name, 'success');
    }
    
    public function deleteUser()
    {
        // Check if user is admin
        if ($this->user->is_admin) {
            $this->dispatch('toast', 'Cannot delete admin users', 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => 'Confirm User Deletion',
            'message' => "Are you sure you want to delete user <strong>{$this->user->name}</strong>?<br>All associated data (activities, workouts, and weeks) will be permanently removed.",
            'confirmButtonText' => 'Delete User',
            'cancelButtonText' => 'Cancel',
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
            $this->dispatch('toast', 'Cannot delete admin users', 'error');
            return;
        }
        
        $name = $this->user->name;
        
        $this->user->weeks()->delete();
        $this->user->activities()->delete();
        $this->user->workouts()->delete();

        $this->user->delete();
        
        $this->dispatch('toast', 'User ' . $name . ' has been successfully deleted', 'success');
        return redirect()->route('admin');
    }

    public function toggleAdmin()
    {
        $this->user->update(['is_admin' => !$this->user->is_admin]);
        $status = $this->user->is_admin ? 'granted' : 'revoked';
        $this->dispatch('toast', 'Admin status ' . $status . ' for ' . $this->user->name, 'success');
    }

    public function verifyEmail()
    {
        $this->user->update(['email_verified_at' => $this->user->email_verified_at ? null : now()]);
        $status = $this->user->email_verified_at ? 'verified' : 'unverified';
        $this->dispatch('toast', 'Email ' . $status . ' for ' . $this->user->name, 'success');
    }

    public function sendResetPassword()
    {
        $token = Password::createToken($this->user);
        $this->user->sendPasswordResetNotification($token);
        $this->dispatch('toast', 'Password reset email sent to ' . $this->user->name, 'success');
    }

    public function resendVerificationEmail()
    {
        $this->user->sendEmailVerificationNotification();
        $this->dispatch('toast', 'Verification email sent to ' . $this->user->name, 'success');
    }

    public function banIpAddress()
    {
        // Check if the user has a registered IP address
        if (!$this->user->last_ip_address) {
            $this->dispatch('toast', 'No IP address available for this user', 'error');
            return;
        }
        
        $this->dispatch('openConfirmModal', [
            'title' => 'Ban IP Address',
            'message' => "Are you sure you want to ban the IP address <strong>{$this->user->last_ip_address}</strong> used by {$this->user->name}?<br>This will prevent anyone using this IP from logging in.",
            'confirmButtonText' => 'Ban IP',
            'cancelButtonText' => 'Cancel',
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
            $this->dispatch('toast', 'No IP address available for this user', 'error');
            return;
        }
        
        // Check if the IP is already banned
        if (\App\Models\BannedIp::isIpBanned($this->user->last_ip_address)) {
            $this->dispatch('toast', 'This IP address is already banned', 'warning');
            return;
        }
        
        // Create ban record
        \App\Models\BannedIp::create([
            'ip_address' => $this->user->last_ip_address,
            'reason' => 'Banned from user profile by administrator',
            'banned_by' => Auth::id(),
        ]);
        
        $this->dispatch('toast', 'IP address ' . $this->user->last_ip_address . ' has been banned', 'success');
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