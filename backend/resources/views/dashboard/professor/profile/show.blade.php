@extends('layouts.dashboard')
@section('title', 'Professor - Profile')
@section('dashboard-content')
<h1>Mon profil</h1>

<div class="card" style="max-width:700px;">
    <p><strong>Nom:</strong> {{ $professor->name }}</p>
    <p><strong>Email:</strong> {{ $professor->email }}</p>
    <p><strong>Telephone:</strong> {{ $professor->phone ?? '-' }}</p>
    <a class="btn" href="{{ route('professor.profile.edit') }}">Modifier</a>
</div>
@endsection
