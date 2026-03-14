@extends('layouts.dashboard')
@section('title', 'Secretary - Create Payment')
@section('dashboard-content')
<div style="max-width:700px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Enregistrer un paiement</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Formulaire d'ajout</div>
    </div>
    <form method="POST" action="{{ route('secretary.payments.store') }}" style="padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem">
        @csrf
        <label style="font-weight:600;color:#2563eb;">Étudiant</label>
        <select name="student_id" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            <option value="">Sélectionner</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                    {{ $student->name }} (solde: {{ number_format((float) $student->account_balance, 2) }})
                </option>
            @endforeach
        </select>

        <label style="font-weight:600;color:#2563eb;">Montant</label>
        <input name="amount" type="number" min="0" step="0.01" value="{{ old('amount') }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Mois</label>
        <input name="month" type="number" min="1" max="12" value="{{ old('month', now()->month) }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Année</label>
        <input name="year" type="number" min="2020" max="2100" value="{{ old('year', now()->year) }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Statut</label>
        <select name="status" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            <option value="paid" @selected(old('status') === 'paid')>paid</option>
            <option value="pending" @selected(old('status') === 'pending')>pending</option>
            <option value="late" @selected(old('status') === 'late')>late</option>
        </select>

        <label style="font-weight:600;color:#2563eb;">Méthode</label>
        <select name="payment_method" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            <option value="cash" @selected(old('payment_method') === 'cash')>cash</option>
            <option value="cmi" @selected(old('payment_method') === 'cmi')>cmi</option>
        </select>

        <label style="font-weight:600;color:#2563eb;">Transaction ID (optionnel)</label>
        <input name="transaction_id" value="{{ old('transaction_id') }}" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Enregistrer</button>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="{{ route('secretary.payments.index') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection

