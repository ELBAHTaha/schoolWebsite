@extends('layouts.app')
@section('title', 'Contact')
@section('content')
<main class="container" style="padding:2rem 0;">
    <h1>Contact form</h1>
    <form method="POST" action="{{ route('visitor.contact.send') }}" class="card">
        @csrf
        <input name="name" placeholder="Name" required>
        <input name="email" type="email" placeholder="Email" required>
        <textarea name="message" placeholder="Your message" required></textarea>
        <button class="btn" type="submit">Send</button>
    </form>
</main>
@endsection
