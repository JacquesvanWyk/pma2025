<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message_type' => 'required|in:general,prayer,partnership,support,resources',
            'message' => 'required|string|max:5000',
            'prayer_request' => 'nullable|boolean',
        ]);

        $validated['prayer_request'] = $request->boolean('prayer_request');

        Mail::to('jvw679@gmail.com')
            ->send(new ContactFormMail($validated));

        return redirect()->route('contact')
            ->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
