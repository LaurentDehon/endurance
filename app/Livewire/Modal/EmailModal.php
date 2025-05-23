<?php

namespace App\Livewire\Modal;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class EmailModal extends Component
{    
    public $userId;
    public $user;
    public $email = [
        'subject' => '',
        'message' => ''
    ];

    protected $rules = [
        'email.subject' => 'required|string|max:255',
        'email.message' => 'required|string',
    ];

    public function mount($userId, $email = null)
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($userId);
        
        if ($email) {
            $this->email = $email;
        } else {
            $this->email = [
                'subject' => __('admin.modal.email.default_subject', ['app_name' => config('app.name')]),
                'message' => __('admin.modal.email.default_message', ['name' => $this->user->name]) . "\n\n",
            ];
        }
    }

    public function sendEmail()
    {
        $this->validate();
        
        $user = User::findOrFail($this->userId);
        
        // Envoyer l'email
        Mail::raw($this->email['message'], function ($message) use ($user) {
            $message->to($user->email)
                ->subject($this->email['subject']);
        });
        
        $this->dispatch('closeModal');
        $this->dispatch('toast', __('admin.modal.email.success_message', ['name' => $user->name]), 'success');
    }

    public function render()
    {
        return view('livewire.modal.email-modal');
    }
}