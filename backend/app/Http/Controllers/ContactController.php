<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Mail::raw($validated['message'], function ($mail) use ($validated) {
            $mail->to('contact@jefalprive.com')
                ->from($validated['email'], $validated['name'])
                ->subject('Nouveau message de contact');
        });

        return response()->json(['success' => true, 'message' => 'Message envoyé avec succès.']);
    }
}

