<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ], [
            'name.required' => __('contact.messages.validation.name_required'),
            'email.required' => __('contact.messages.validation.email_required'),
            'email.email' => __('contact.messages.validation.email_valid'),
            'subject.required' => __('contact.messages.validation.subject_required'),
            'message.required' => __('contact.messages.validation.message_required'),
        ]);

        $recipient = config('mail.from.address');

        if (!$recipient) {
            throw new \Exception('The mail recipient is empty.');
        }

        try {
            Mail::to($recipient)->send(new ContactMail($request->all()));
            
            session()->flash('toast', [
                'message' => __('contact.messages.success'),
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            session()->flash('toast', [
                'message' => __('contact.messages.error'),
                'type' => 'error'
            ]);
        }

        return back();
    }
}