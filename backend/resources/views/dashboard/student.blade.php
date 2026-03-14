@extends('layouts.dashboard')
@section('title', 'Dashboard Etudiant')
@section('dashboard-content')
<h1 style="font-size:2rem;font-weight:700;margin-bottom:1.5rem;color:#1e293b;">Dashboard Etudiant</h1>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">
    @foreach($stats as $label => $value)
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $value }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">{{ $label }}</small>
        </div>
    @endforeach
</div>
@endsection

