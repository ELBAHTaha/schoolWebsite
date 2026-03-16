@extends('layouts.app')
@section('title', 'Pre-registration')
@section('content')
<main class="container" style="padding:2rem 0;">
    <h1>Pre-registration form</h1>
    <form method="POST" action="{{ route('visitor.pre-registration.store') }}" class="card">
        @csrf
        <input name="name" placeholder="Name" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="desired_program" placeholder="Desired program" required>
        <input name="phone" placeholder="Phone">
        <textarea name="message" placeholder="Message (optional)"></textarea>
        <button class="btn" type="submit">Submit pre-registration</button>
    </form>
</main>
@endsection
