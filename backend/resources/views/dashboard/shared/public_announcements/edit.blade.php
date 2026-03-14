@extends('layouts.dashboard')
@section('title', 'Edit Public Announcement')
@section('dashboard-content')
<div style="max-width:760px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Modifier l'annonce publique</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Formulaire de modification</div>
    </div>
    <form method="POST" action="{{ route($routePrefix.'.announcements.update', $announcement) }}" style="padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem">
        @csrf
        @method('PUT')
        <label style="font-weight:600;color:#2563eb;">Titre</label>
        <input name="title" value="{{ old('title', $announcement->title) }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Contenu</label>
        <textarea name="content" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">{{ old('content', $announcement->content) }}</textarea>

        <label style="font-weight:600;color:#2563eb;">Date de début</label>
        <input name="start_date" type="date" value="{{ old('start_date', $announcement->start_date?->format('Y-m-d')) }}" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Date de fin</label>
        <input name="end_date" type="date" value="{{ old('end_date', $announcement->end_date?->format('Y-m-d')) }}" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Mettre à jour</button>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="{{ route($routePrefix.'.announcements.index') }}">Retour</a>
        </div>
    </form>
</div>
@endsection

