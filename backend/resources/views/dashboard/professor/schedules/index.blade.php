@extends('layouts.dashboard')
@section('title', 'Professor - Schedule')
@section('dashboard-content')
<div style="max-width:900px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Mon emploi du temps</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue d√©taill√©e de vos cr√©neaux</div>
    </div>
    <div style="padding:1.5rem 1.2rem;margin-bottom:1.6rem">
        <h3 style="margin:0 0 1rem 0;font-size:1.1rem;color:#2563eb;font-weight:700;">DisponibilitÈs (horaires de travail)</h3>
        @if($workingHours->isEmpty())
            <div style="color:#64748b;">Aucun crÈneau dÈfini.</div>
        @else
            <table style="width:100%;border-collapse:separate;border-spacing:0 .6rem;font-size:1.02rem;">
                <thead>
                    <tr style="color:#64748b;text-transform:uppercase;font-size:.78rem;letter-spacing:.04em;">
                        <th style="text-align:left;padding:.4rem .6rem;">Jour</th>
                        <th style="text-align:left;padding:.4rem .6rem;">DÈbut</th>
                        <th style="text-align:left;padding:.4rem .6rem;">Fin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workingHours as $wh)
                        <tr style="background:#f8fafc;border-radius:.6rem;">
                            <td style="padding:.6rem .6rem;">{{ $wh->day_of_week }}</td>
                            <td style="padding:.6rem .6rem;">{{ $wh->starts_at }}</td>
                            <td style="padding:.6rem .6rem;">{{ $wh->ends_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div><div style="padding:2rem 1.2rem">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Classe</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Jour</th>
                        <th style="padding:.9rem .7rem;text-align:left;">D√©but</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Fin</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Salle</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($schedules as $schedule)
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;">{{ $schedule->schoolClass?->name ?? '-' }}</td>
                        <td style="padding:.8rem .7rem;">{{ $schedule->day_of_week }}</td>
                        <td style="padding:.8rem .7rem;">{{ $schedule->starts_at }}</td>
                        <td style="padding:.8rem .7rem;">{{ $schedule->ends_at }}</td>
                        <td style="padding:.8rem .7rem;">{{ $schedule->location ?? $schedule->schoolClass?->room?->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding:.8rem .7rem;text-align:center;color:#64748b;">Aucun cr√©neau assign√©.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:1.5rem;">
            {{ $schedules->links() }}
        </div>
    </div>
</div>
@endsection


