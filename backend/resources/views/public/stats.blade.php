@extends('layouts.app')
@section('title', 'Public Statistics')
@section('content')
<main class="container" style="padding:2rem 0;">
    <h1>Public statistics</h1>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;">
        <div class="card"><strong>{{ $stats['students'] }}</strong><br><small>Students</small></div>
        <div class="card"><strong>{{ $stats['professors'] }}</strong><br><small>Professors</small></div>
        <div class="card"><strong>{{ $stats['classes'] }}</strong><br><small>Classes</small></div>
    </div>
</main>
@endsection
