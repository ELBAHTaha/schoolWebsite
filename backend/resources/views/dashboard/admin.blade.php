@extends('layouts.dashboard')
@section('title', 'Dashboard Admin')
@section('dashboard-content')
<div style="min-height:100vh;width:100%;background:#fff;padding:0;margin:-2rem -2rem 0 -2rem;">
    <div style="max-width:1100px;margin:0 auto;padding:3rem 1.5rem 2rem 1.5rem;">
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2.5rem;">
            <div style="background:#2563eb;padding:.9rem;border-radius:1rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);display:flex;align-items:center;justify-content:center;">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="3" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M7 8h10M7 12h10M7 16h6" stroke="#2563eb" stroke-width="2" fill="none"/></svg>
            </div>
            <div>
                <h1 style="font-size:2.5rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Dashboard Administrateur</h1>
                <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue d'ensemble</div>
            </div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">
            @foreach($stats as $label => $value)
                <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s">
                    <strong style="font-size:2.2rem;color:#2563eb;">{{ $value }}</strong>
                    <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">{{ $label }}</small>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

