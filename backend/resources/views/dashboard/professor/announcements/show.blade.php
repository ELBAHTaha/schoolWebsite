@extends('layouts.dashboard')
@section('title', 'Professor - Announcement Details')
@section('dashboard-content')
<h1>{{ $announcement->title }}</h1>

<div class="card" style="max-width:800px;">
    <p><strong>Classe:</strong> {{ $announcement->schoolClass?->name ?? '-' }}</p>
    <p><strong>Période:</strong> {{ $announcement->start_date?->format('Y-m-d') ?? '-' }} → {{ $announcement->end_date?->format('Y-m-d') ?? '-' }}</p>
    <p><strong>Contenu:</strong></p>
    <p>{{ $announcement->content }}</p>
    <div style="display:flex;gap:.6rem;">
        <a class="btn" href="{{ route('professor.announcements.edit', $announcement) }}">Modifier</a>
        <a class="btn" href="{{ route('professor.announcements.index') }}">Retour</a>
    </div>
</div>
@endsection
