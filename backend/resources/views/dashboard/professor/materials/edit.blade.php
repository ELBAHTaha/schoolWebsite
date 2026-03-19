@extends('layouts.dashboard')
@section('title', 'Professor - Edit Material')
@section('dashboard-content')
<div style="max-width:740px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Modifier le support</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Formulaire de modification</div>
    </div>
    <form method="POST" action="{{ route('professor.materials.update', $material) }}" enctype="multipart/form-data" style="padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem">
        @csrf
        @method('PUT')
        <label style="font-weight:600;color:#2563eb;">Classe</label>
        <select name="class_id" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected(old('class_id', $material->class_id) == $class->id)>{{ $class->name }}</option>
            @endforeach
        </select>

        <label style="font-weight:600;color:#2563eb;">Titre</label>
        <input name="title" value="{{ old('title', $material->title) }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Remplacer le PDF (optionnel)</label>
        <input type="file" name="pdf" accept="application/pdf" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Mettre à jour</button>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="{{ route('professor.materials.show', $material) }}">Retour</a>
        </div>
    </form>
</div>
@endsection


