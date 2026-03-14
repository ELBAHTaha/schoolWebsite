@extends('layouts.dashboard')
@section('title', 'Student - Schedule')
@section('dashboard-content')
<div style="max-width:1100px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;display:flex;align-items:center;justify-content:space-between;gap:1.5rem;flex-wrap:wrap;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Mon emploi du temps</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue calendrier hebdomadaire</div>
            <div style="color:#2563eb;font-size:1.08rem;margin-top:.5rem;font-weight:600;">
                Semaine du {{ $startOfWeek->format('d/m/Y') }} au {{ $endOfWeek->format('d/m/Y') }} (Semaine {{ $week }})
            </div>
        </div>
        <div style="display:flex;gap:.7rem;align-items:center;">
            <a href="?week={{ $week-1 }}&year={{ $year }}" style="background:#2563eb;color:#fff;padding:.6rem 1.1rem;border-radius:.7rem;font-weight:600;text-decoration:none;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);font-size:1.05rem;">&larr; Semaine précédente</a>
            <a href="?week={{ $week+1 }}&year={{ $year }}" style="background:#2563eb;color:#fff;padding:.6rem 1.1rem;border-radius:.7rem;font-weight:600;text-decoration:none;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);font-size:1.05rem;">Semaine suivante &rarr;</a>
        </div>
    </div>
    <div style="padding:2rem 1.2rem;overflow-x:auto">
        @php
            $jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
            $slots = [];
            $minHour = 23;
            $maxHour = 0;
            foreach($schedules as $s) {
                $slots[$s->day_of_week][] = $s;
                $start = intval(substr($s->starts_at,0,2));
                $end = intval(substr($s->ends_at,0,2));
                if ($start < $minHour) $minHour = $start;
                if ($end > $maxHour) $maxHour = $end;
            }
            if (count($schedules) === 0) {
                $minHour = 7;
                $maxHour = 20;
            }
            $heures = range($minHour, $maxHour-1);
        @endphp
        <table style="width:100%;min-width:900px;border-collapse:separate;border-spacing:0;">
            <thead>
                <tr>
                    <th style="width:70px;background:#f1f5f9;color:#2563eb;font-size:1.08rem;padding:.7rem .5rem;text-align:center;border-top-left-radius:.7rem;">Heure</th>
                    @foreach($jours as $j)
                        <th style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;padding:.7rem .5rem;text-align:center;">{{ $j }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($heures as $h)
                <tr>
                    <td style="background:#f1f5f9;color:#64748b;font-weight:600;text-align:center;padding:.6rem .5rem;">{{ $h }}h</td>
                    @foreach($jours as $j)
                        <td style="height:54px;position:relative;padding:0.2rem .2rem;">
                            @php
                                $daySchedules = collect($slots[$j] ?? [])->filter(function($s) use($h) {
                                    $start = intval(substr($s->starts_at,0,2));
                                    $end = intval(substr($s->ends_at,0,2));
                                    return $start <= $h && $end > $h;
                                });
                            @endphp
                            @foreach($daySchedules as $s)
                                <div style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:#fff;border-radius:.6rem;padding:.3rem .6rem;font-size:.98rem;font-weight:600;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);margin-bottom:.2rem;">
                                    {{ $s->location ? '['.$s->location.'] ' : '' }}{{ $s->starts_at }}-{{ $s->ends_at }}<br>{{ $s->schoolClass->name ?? '' }}
                                </div>
                            @endforeach
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:1.5rem;color:#64748b;font-size:.98rem;">Chaque case colorée correspond à un créneau de cours. Les horaires sont affichés de 7h à 20h.</div>
    </div>
</div>
@endsection

