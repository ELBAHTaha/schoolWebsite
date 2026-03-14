@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('dashboard-content')
<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Dashboard Administrateur</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue d'ensemble</div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;margin-bottom:2.2rem;">
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['total_students'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Total students</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['monthly_revenue'] }} MAD</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Monthly revenue</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['payments_received'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Payments received</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['global_users'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Global users</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['global_classes'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Global classes</small>
        </div>
    </div>
</div>
@endsection

