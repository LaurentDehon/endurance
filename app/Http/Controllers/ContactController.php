<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use TallStackUi\Traits\Interactions;

class ContactController extends Controller
{
    use Interactions; 

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
            throw new \Exception('Le destinataire du mail est vide.');
        }

        Mail::to($recipient)->send(new ContactMail($request->all()));
        $this->toast()->success('Votre message a été envoyé avec succès !')->send();

        return back();
    }

}