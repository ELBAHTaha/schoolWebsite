<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PublicFormController extends Controller
{
    public function contact(Request $request): JsonResponse
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

        return response()->json([
            'message' => 'Message sent.',
        ]);
    }

    public function preRegistration(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'desired_program' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['nullable', 'string'],
        ]);

        return response()->json([
            'message' => 'Pre-registration submitted.',
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user = User::create([
            ...$validated,
            'role' => 'visitor',
        ]);

        return response()->json([
            'message' => 'Visitor account created.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ], 201);
    }
}
