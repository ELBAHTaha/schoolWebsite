@extends('layouts.app')

@section('content')
<div style="display:flex;min-height:100vh;">
    @include('components.sidebar')

    <div style="flex:1;display:flex;flex-direction:column;">
        <main style="width:100%;min-height:100vh;padding:0;margin:0;background:none;">
            @if(session('status'))
                <div class="card" style="margin-bottom:1rem;border-left:4px solid #16a34a;">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="card" style="margin-bottom:1rem;border-left:4px solid #dc2626;">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="card" style="margin-bottom:1rem;border-left:4px solid #dc2626;">
                    <strong>Validation errors:</strong>
                    <ul style="margin:.5rem 0 0 1rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('dashboard-content')
        </main>
    </div>
</div>
@endsection
