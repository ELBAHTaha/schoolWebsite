@extends('layouts.dashboard')
@section('title', 'Professor - Profile')
@section('dashboard-content')
<h1>Modifier mon profil</h1>
<form method="POST" action="{{ route('professor.profile.update') }}" class="card">
    @csrf @method('PUT')
    <label>Name</label>
    <input name="name" value="{{ $professor->name }}" required>
    <label>Phone</label>
    <input name="phone" value="{{ $professor->phone }}">
    <label>Nouveau mot de passe (optionnel)</label>
    <input name="password" type="password">
    <label>Confirmation du mot de passe</label>
    <input name="password_confirmation" type="password">
    <div style="display:flex;gap:.6rem;">
        <button class="btn" type="submit">Mettre a jour</button>
        <a class="btn" href="{{ route('professor.profile.show') }}">Retour</a>
    </div>
</form>
@endsection
