@extends('layouts.app')
@section('title', 'Accueil')
@section('content')
<header style="background:#0f766e;color:#fff;padding:1rem 0;">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;">
        <strong>JEFAL Prive</strong>
        <a href="{{ route('login') }}" class="btn" style="background:#fff;color:#0f766e;">Connexion</a>
    </div>
</header>
<main class="container" style="padding:2rem 0;">
    <div class="card">
        <h1>Institut de langues JEFAL Prive</h1>
        <p>Plateforme de gestion scolaire: cours, paiements, annonces et ressources PDF.</p>
        <p style="display:flex;gap:.5rem;flex-wrap:wrap;">
            <a class="btn" href="{{ route('visitor.stats') }}">Public statistics</a>
            <a class="btn" href="{{ route('visitor.contact') }}">Contact</a>
            <a class="btn" href="{{ route('visitor.create-account') }}">Create visitor account</a>
            <a class="btn" href="{{ route('visitor.pre-registration') }}">Pre-registration</a>
        </p>
    </div>
</main>
@endsection
