@extends('layouts.dashboard')
@section('title', 'Professor - Material Details')
@section('dashboard-content')
<h1>{{ $material->title }}</h1>

<div class="card" style="max-width:760px;">
    <p><strong>Classe:</strong> {{ $material->schoolClass?->name ?? '-' }}</p>
    <p><strong>Fichier:</strong> <a href="{{ asset('storage/'.$material->file_path) }}" target="_blank">Ouvrir le PDF</a></p>
    <div style="display:flex;gap:.6rem;">
        <a class="btn" href="{{ route('professor.materials.edit', $material) }}">Modifier</a>
        <a class="btn" href="{{ route('professor.materials.index') }}">Retour</a>
    </div>
</div>
@endsection
