@extends('layouts.dashboard')
@section('title', 'Professor - Assignment Details')
@section('dashboard-content')
<h1>{{ $assignment->title }}</h1>

<div class="card" style="max-width:800px;">
    <p><strong>Classe:</strong> {{ $assignment->schoolClass?->name ?? '-' }}</p>
    <p><strong>Date limite:</strong> {{ ($assignment->due_date ?? $assignment->deadline)?->format('Y-m-d') }}</p>
    @if($assignment->document_path)
        <p><strong>Document:</strong> <a href="{{ asset('storage/'.$assignment->document_path) }}" target="_blank" rel="noopener">Télécharger</a></p>
    @endif
    <p><strong>Description:</strong></p>
    <p>{{ $assignment->description ?? '-' }}</p>
    <div style="display:flex;gap:.6rem;">
        <a class="btn" href="{{ route('professor.assignments.edit', $assignment) }}">Modifier</a>
        <a class="btn" href="{{ route('professor.assignments.index') }}">Retour</a>
    </div>
</div>
@endsection
