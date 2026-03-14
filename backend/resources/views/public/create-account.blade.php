@extends('layouts.app')
@section('title', 'Create Visitor Account')
@section('content')
<main class="container" style="padding:2rem 0;">
    <h1>Create visitor account</h1>
    <form method="POST" action="{{ route('visitor.create-account.store') }}" class="card">
        @csrf
        <input name="name" placeholder="Name" required>
        <input name="email" type="email" placeholder="Email" required>
        <input name="password" type="password" placeholder="Password" required>
        <input name="phone" placeholder="Phone">
        <button class="btn" type="submit">Create account</button>
    </form>
</main>
@endsection
