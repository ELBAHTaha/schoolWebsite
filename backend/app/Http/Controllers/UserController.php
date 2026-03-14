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
        $user = User::create($request->validated());

        Mail::to($user->email)->queue(new AccountCreatedMail($user));

        return back()->with('status', 'Compte utilisateur cree avec succes.');
    }
}
