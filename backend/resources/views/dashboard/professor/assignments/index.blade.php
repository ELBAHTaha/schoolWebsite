@extends('layouts.dashboard')
@section('title', 'Professor - Assignments')
@section('dashboard-content')
<div style="max-width:1200px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Devoirs</h1>
        <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="{{ route('professor.assignments.create') }}">Créer un devoir</a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:1.5rem;">
        @forelse($assignments as $assignment)
            <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.1rem;justify-content:space-between">
                <div>
                    <div style="font-size:1.18rem;font-weight:700;color:#2563eb;margin-bottom:.3rem;">{{ $assignment->title }}</div>
                    <div style="color:#64748b;font-size:1.05rem;">Classe : <span style="color:#334155;">{{ $assignment->schoolClass?->name }}</span></div>
                    <div style="color:#64748b;font-size:1.05rem;">Date limite : <span style="color:#334155;">{{ ($assignment->due_date ?? $assignment->deadline)?->format('Y-m-d') }}</span></div>
                </div>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                    <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;" href="{{ route('professor.assignments.show', $assignment) }}">Voir</a>
                    <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;" href="{{ route('professor.assignments.edit', $assignment) }}">Modifier</a>
                    <form method="POST" action="{{ route('professor.assignments.destroy', $assignment) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn" style="background:#ef4444;color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;border:none;" type="submit" onclick="return confirm('Supprimer ce devoir ?')">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div style="color:#64748b;font-size:1.1rem;">Aucun devoir trouvé.</div>
        @endforelse
    </div>
    <div style="margin-top:2.2rem;">
        {{ $assignments->links() }}
    </div>
</div>
@endsection


