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
        ]);

        $recipient = config('mail.from.address');

        if (!$recipient) {
            throw new \Exception('The mail recipient is empty.');
        }

        Mail::to($recipient)->send(new ContactMail($request->all()));
        
        session()->flash('toast', [
            'message' => 'Your message has been sent successfully',
            'type' => 'success'
        ]);

        return back();
    }
}