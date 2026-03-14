@extends('layouts.dashboard')
@section('title', 'Student Dashboard')
@section('dashboard-content')
<div style="max-width:1200px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Tableau de bord étudiant</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue d'ensemble de votre scolarité</div>
    </div>
    <div style="padding:1.2rem 1.2rem;margin-bottom:1.6rem">
        <h3 style="margin:0 0 .6rem 0;font-size:1.15rem;color:#2563eb;font-weight:700;">Votre cours</h3>
        @forelse($classes as $class)
            <div style="color:#334155;font-size:1.05rem;margin-bottom:.35rem;">
                <strong>{{ $class->name }}</strong>
                <span style="color:#64748b;">@if($class->professor) - Prof: {{ $class->professor->name }}@endif</span>
                <span style="color:#64748b;">@if($class->room) - Salle: {{ $class->room->name }}@endif</span>
            </div>
        @empty
            <div style="color:#64748b;font-size:1.05rem;">Aucun cours assigné.</div>
        @endforelse
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:2.2rem;">
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['assignments'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Devoirs</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['pdfs'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Supports PDF</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['schedules'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Créneaux</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center">
            <strong style="font-size:2.2rem;color:#ef4444;">{{ $stats['unpaid_payments'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Paiements impayés</small>
        </div>
        <div style="padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center">
            <strong style="font-size:2.2rem;color:#2563eb;">{{ $stats['announcements'] }}</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Annonces</small>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:1.5rem;">
        <div style="padding:1.5rem 1.2rem">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Devoirs</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($assignments as $item)
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;">{{ $item->title }} <span style="color:#64748b;">- {{ $item->deadline?->format('Y-m-d') }}</span></li>
                @endforeach
            </ul>
        </div>
        <div style="padding:1.5rem 1.2rem">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Supports PDF</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($pdfs as $item)
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;">{{ $item->title }}</li>
                @endforeach
            </ul>
        </div>
        <div style="padding:1.5rem 1.2rem">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Emploi du temps</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($schedules as $item)
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;">{{ $item->day_of_week }} <span style="color:#64748b;">{{ $item->starts_at }}-{{ $item->ends_at }}</span></li>
                @endforeach
            </ul>
        </div>
        <div style="padding:1.5rem 1.2rem">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Paiements</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($payments as $item)
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;">{{ $item->month }}/{{ $item->year }} <span style="color:#64748b;">- {{ $item->status }} - {{ $item->amount }}</span></li>
                @endforeach
            </ul>
        </div>
        <div style="padding:1.5rem 1.2rem">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Annonces</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                @foreach($announcements as $item)
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;">{{ $item->title }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection

