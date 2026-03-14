<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePdfRequest;
use App\Models\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfController extends Controller
{
    public function store(StorePdfRequest $request): RedirectResponse
    {
        $path = $request->file('pdf')->store('pdfs', 'public');

        Pdf::create([
            'class_id' => $request->integer('class_id'),
            'title' => $request->string('title')->toString(),
            'file_path' => $path,
            'uploaded_by' => $request->user()->id,
        ]);

        return back()->with('status', 'Document PDF televerse avec succes.');
    }

    public function download(Request $request, Pdf $pdf): StreamedResponse
    {
        $student = $request->user();

        $isEnrolled = $student->enrolledClasses()->where('classes.id', $pdf->class_id)->exists();
        abort_unless($student->hasRole('student') && $isEnrolled, 403);

        abort_unless(Storage::disk('public')->exists($pdf->file_path), 404);

        return Storage::disk('public')->download($pdf->file_path, $pdf->title.'.pdf');
    }
}
