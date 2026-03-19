@extends('layouts.dashboard')
@section('title', 'Student - Assignments')
@section('dashboard-content')
<div style="max-width:900px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Mes devoirs</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Liste de tous vos devoirs</div>
    </div>
    <div style="padding:2rem 1.2rem">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Titre</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Description</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Date limite</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Document</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($assignments as $assignment)
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;">{{ $assignment->title }}</td>
                        <td style="padding:.8rem .7rem;">{{ $assignment->description }}</td>
                        <td style="padding:.8rem .7rem;">{{ $assignment->deadline?->format('Y-m-d') }}</td>
                        <td style="padding:.8rem .7rem;">
                            @if($assignment->document_path)
                                <a href="{{ asset('storage/'.$assignment->document_path) }}" target="_blank" rel="noopener">Télécharger</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:1rem;text-align:center;color:#64748b;">Aucun devoir pour le moment.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


