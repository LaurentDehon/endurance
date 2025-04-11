<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class EmailForm extends Component
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
                'subject' => 'Message from ' . config('app.name'),
                'message' => "Hello {$this->user->name},\n\n",
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
        $this->dispatch('toast', 'Email sent successfully to ' . $user->name, 'success');
    }

    public function render()
    {
        $name = $this->user->name;
        return view('livewire.email-form', compact('name'));
    }
}