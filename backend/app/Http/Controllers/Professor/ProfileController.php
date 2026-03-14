<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        return view('dashboard.professor.profile.show', ['professor' => $request->user()]);
    }

    public function edit(Request $request): View
    {
        return view('dashboard.professor.profile.edit', ['professor' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $request->user()->update($validated);

        return redirect()->route('professor.profile.show')->with('status', 'Profil mis a jour.');
    }
}
