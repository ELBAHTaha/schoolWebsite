@extends('layouts.dashboard')
@section('title', 'Secretary - Students')
@section('dashboard-content')
<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Gestion des étudiants</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Liste des étudiants inscrits et gestion</div>
        </div>
        <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;transition:background .2s;" href="{{ route('secretary.students.create') }}">Créer un étudiant</a>
    </div>
    <div style="padding:2rem 1.2rem">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:800px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Nom</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Email</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Classe</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Statut paiement</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Solde restant</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;">{{ $student->name }}</td>
                        <td style="padding:.8rem .7rem;">{{ $student->email }}</td>
                        <td style="padding:.8rem .7rem;">{{ $student->schoolClass?->name ?? '-' }}</td>
                        <td style="padding:.8rem .7rem;">{{ $student->payment_status }}</td>
                        <td style="padding:.8rem .7rem;">{{ number_format((float) $student->account_balance, 2) }}</td>
                        <td style="padding:.8rem .7rem;">
                            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;margin-right:.5rem;" href="{{ route('secretary.students.show', $student) }}">Voir</a>
                            <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;" href="{{ route('secretary.students.edit', $student) }}">Modifier</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:1.5rem;">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection


