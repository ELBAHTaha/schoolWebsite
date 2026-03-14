<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class VisitorController extends Controller
{
    public function home(): View
    {
        return view('public.home');
    }

    public function stats(): View
    {
        $stats = [
            'students' => User::where('role', 'student')->count(),
            'professors' => User::where('role', 'professor')->count(),
            'classes' => SchoolClass::count(),
        ];

        return view('public.stats', compact('stats'));
    }

    public function contactForm(): View
    {
        return view('public.contact');
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        Mail::raw(
            "Contact message from {$validated['name']} ({$validated['email']}):\n\n{$validated['message']}",
            static function ($message): void {
                $message->to(config('mail.from.address'))->subject('Visitor contact form');
            }
        );

        return back()->with('status', 'Message sent.');
    }

    public function createAccountForm(): View
    {
        return view('public.create-account');
    }

    public function createAccount(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        User::create([
            ...$validated,
            'role' => 'visitor',
        ]);

        return redirect()->route('login')->with('status', 'Visitor account created.');
    }

    public function preRegistrationForm(): View
    {
        return view('public.pre-registration');
    }

    public function preRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'desired_program' => ['required', 'string', 'max:255'],
        ]);

        return back()->with('status', 'Pre-registration submitted.');
    }
}
