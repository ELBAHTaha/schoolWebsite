<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHomeworkRequest;
use App\Models\Homework;
use Illuminate\Http\RedirectResponse;

class HomeworkController extends Controller
{
    public function store(StoreHomeworkRequest $request): RedirectResponse
    {
        Homework::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Devoir ajoute avec succes.');
    }
}
