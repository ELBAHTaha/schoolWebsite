@extends('layouts.dashboard')
@section('title', 'Admin - Financial Reports')
@section('dashboard-content')
<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Gestion des Paiements</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Suivi et synthèse des paiements de l'établissement</div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.2rem;margin-bottom:2.2rem;">
        <div style="padding:1.3rem 1.2rem;display:flex;align-items:center;gap:.8rem">
            <span style="background:#e0e7ff;color:#2563eb;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#fff" stroke="#2563eb" stroke-width="2"/><path d="M7 13h10v2a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2v-2Zm0-2V9a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2H7Z" fill="#2563eb"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;">{{ $stats['total'] ?? '-' }}</div>
                <div style="color:#64748b;font-size:.98rem;">Total paiements</div>
            </div>
        </div>
        <div style="padding:1.3rem 1.2rem;display:flex;align-items:center;gap:.8rem">
            <span style="background:#d1fae5;color:#059669;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#059669" stroke-width="2" fill="#fff"/><path d="M9 12l2 2 4-4" stroke="#059669" stroke-width="2" fill="none"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;">{{ $stats['paid'] ?? '-' }}</div>
                <div style="color:#64748b;font-size:.98rem;">Payés</div>
            </div>
        </div>
        <div style="padding:1.3rem 1.2rem;display:flex;align-items:center;gap:.8rem">
            <span style="background:#e0e7ff;color:#2563eb;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M15 9l-6 6" stroke="#2563eb" stroke-width="2" fill="none"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;">{{ $stats['unpaid'] ?? '-' }}</div>
                <div style="color:#64748b;font-size:.98rem;">Impayés</div>
            </div>
        </div>
        <div style="padding:1.3rem 1.2rem;display:flex;align-items:center;gap:.8rem">
            <span style="background:#fef3c7;color:#d97706;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path d="M12 8v4l3 3" stroke="#d97706" stroke-width="2" fill="none"/><circle cx="12" cy="12" r="10" stroke="#d97706" stroke-width="2" fill="#fff"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;">{{ $stats['pending'] ?? '-' }}</div>
                <div style="color:#64748b;font-size:.98rem;">En attente</div>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:1rem;margin-bottom:2.2rem;flex-wrap:wrap;">
        <input type="text" id="searchInput" placeholder="Rechercher..." style="flex:1 1 300px;min-width:220px;padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
        <select style="padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
            <option>Toutes les années</option>
            @foreach($years as $year)
                <option>{{ $year }}</option>
            @endforeach
        </select>
        <select style="padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
            <option>Tous les mois</option>
            @foreach($months as $month)
                <option>{{ $month }}</option>
            @endforeach
        </select>
    </div>
    <div style="display:flex;justify-content:center;margin-top:1.5rem;">
        <table style="width:100%;max-width:1100px;border-collapse:separate;border-spacing:0 0.7rem;font-size:1.08rem;overflow:hidden">
            <thead>
                <tr style="background:linear-gradient(90deg,#2563eb 60%,#0ea5e9 100%);color:#fff;">
                    <th style="padding:1rem 1.2rem;border-top-left-radius:1rem;text-align:left;font-weight:700;">Année</th>
                    <th style="padding:1rem 1.2rem;text-align:left;font-weight:700;">Mois</th>
                    <th style="padding:1rem 1.2rem;text-align:left;font-weight:700;">Total Paiement</th>
                    <th style="padding:1rem 1.2rem;border-top-right-radius:1rem;text-align:left;font-weight:700;">Paiements</th>
                </tr>
            </thead>
            <tbody>
            @foreach($monthlyReports as $report)
                <tr style="background:#f8fafc;color:#1e293b;box-shadow:0 1px 4px 0 rgba(16,24,40,0.04);border-radius:.7rem;transition:background .2s;">
                    <td style="padding:1rem 1.2rem;border-radius:.7rem 0 0 .7rem;">{{ $report->year }}</td>
                    <td style="padding:1rem 1.2rem;">{{ $report->month }}</td>
                    <td style="padding:1rem 1.2rem;font-weight:600;color:#2563eb;">{{ number_format((float)$report->total_amount, 2) }} MAD</td>
                    <td style="padding:1rem 1.2rem;border-radius:0 .7rem .7rem 0;">{{ $report->payment_count }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


