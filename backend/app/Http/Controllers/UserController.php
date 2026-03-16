<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Mail\AccountCreatedMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $plainPassword = $validated['password'];
        unset($validated['working_hours']);

        $user = User::create($validated);

        $loginUrl = rtrim(config('app.frontend_url'), '/') . '/login';
        Mail::to($user->email)->send(new AccountCreatedMail($user, $plainPassword, $loginUrl));

        return back()->with('status', 'Compte utilisateur cree avec succes.');
    }
}
