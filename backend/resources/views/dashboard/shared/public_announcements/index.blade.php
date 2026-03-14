@extends('layouts.dashboard')
@section('title', 'Public Announcements')
@section('dashboard-content')
<div style="max-width:1100px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Annonces publiques</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Promotions et actualités visibles sur la page d'accueil</div>
        </div>
    </div>

    <form method="POST" action="{{ route($routePrefix.'.announcements.store') }}" style="padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem;margin-bottom:2rem">
        @csrf
        <label style="font-weight:600;color:#2563eb;">Titre</label>
        <input name="title" value="{{ old('title') }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Contenu</label>
        <textarea name="content" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">{{ old('content') }}</textarea>

        <label style="font-weight:600;color:#2563eb;">Date de début</label>
        <input name="start_date" type="date" value="{{ old('start_date') }}" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Date de fin</label>
        <input name="end_date" type="date" value="{{ old('end_date') }}" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Publier</button>
        </div>
    </form>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.5rem;">
        @forelse($announcements as $announcement)
            <div style="padding:1.6rem 1.2rem;display:flex;flex-direction:column;gap:1rem">
                <div>
                    <div style="font-size:1.18rem;font-weight:700;color:#2563eb;margin-bottom:.3rem;">{{ $announcement->title }}</div>
                    <div style="color:#64748b;font-size:1.02rem;">{{ $announcement->content }}</div>
                    <div style="color:#64748b;font-size:0.98rem;margin-top:.35rem;">
                        Période :
                        <span style="color:#334155;">
                            {{ $announcement->start_date?->format('Y-m-d') ?? '-' }} →
                            {{ $announcement->end_date?->format('Y-m-d') ?? '-' }}
                        </span>
                    </div>
                </div>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                    <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;" href="{{ route($routePrefix.'.announcements.edit', $announcement) }}">Modifier</a>
                    <form method="POST" action="{{ route($routePrefix.'.announcements.destroy', $announcement) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn" style="background:#ef4444;color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;border:none;" type="submit" onclick="return confirm('Supprimer cette annonce ?')">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div style="color:#64748b;font-size:1.1rem;">Aucune annonce publique.</div>
        @endforelse
    </div>
    <div style="margin-top:2.2rem;">
        {{ $announcements->links() }}
    </div>
</div>
@endsection

